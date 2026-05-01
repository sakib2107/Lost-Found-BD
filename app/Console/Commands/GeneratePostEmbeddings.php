<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Services\ClipEmbeddingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GeneratePostEmbeddings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:generate-embeddings 
                            {--limit=0 : Number of posts to process at once (0 for all)}
                            {--force : Regenerate embeddings for posts that already have them}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate CLIP embeddings for posts with images';

    protected $clipService;

    public function __construct(ClipEmbeddingService $clipService)
    {
        parent::__construct();
        $this->clipService = $clipService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limit = $this->option('limit');
        $force = $this->option('force');

        $this->info('Starting embedding generation process...');

        // Get posts that need embeddings
        $query = Post::whereNotNull('images');
        
        if (!$force) {
            $query->whereNull('embeddings');
        }

        if ($limit > 0) {
            $posts = $query->limit($limit)->get();
        } else {
            $posts = $query->get();
        }

        if ($posts->isEmpty()) {
            $this->info('No posts found that need embedding generation.');
            return 0;
        }

        $this->info("Found {$posts->count()} posts to process.");

        $progressBar = $this->output->createProgressBar($posts->count());
        $progressBar->start();

        $successCount = 0;
        $failureCount = 0;

        foreach ($posts as $post) {
            try {
                $embedding = $this->clipService->generateEmbeddingFromStoragePath($post->images);
                
                if ($embedding) {
                    $post->update(['embeddings' => $embedding]);
                    $successCount++;
                    
                    Log::info('Generated embedding for post', [
                        'post_id' => $post->id,
                        'image_path' => $post->images
                    ]);
                } else {
                    $failureCount++;
                    
                    Log::warning('Failed to generate embedding for post', [
                        'post_id' => $post->id,
                        'image_path' => $post->images
                    ]);
                }
            } catch (\Exception $e) {
                $failureCount++;
                
                Log::error('Exception during embedding generation', [
                    'post_id' => $post->id,
                    'image_path' => $post->images,
                    'error' => $e->getMessage()
                ]);
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();

        $this->info("Embedding generation completed!");
        $this->info("Successful: {$successCount}");
        $this->info("Failed: {$failureCount}");

        if ($failureCount > 0) {
            $this->warn("Some embeddings failed to generate. Check the logs for details.");
        }

        return 0;
    }
}