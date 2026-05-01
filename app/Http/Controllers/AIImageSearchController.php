<?php

namespace App\Http\Controllers;

use App\Services\ClipEmbeddingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AIImageSearchController extends Controller
{
    protected $clipService;

    public function __construct(ClipEmbeddingService $clipService)
    {
        $this->clipService = $clipService;
    }

    /**
     * Show the AI image search form
     */
    public function index()
    {
        // Clean up any previous search images for this session
        $this->cleanupPreviousSearchImages();
        
        return view('ai-search.index');
    }

    /**
     * Search for similar posts using uploaded image
     */
    public function search(Request $request)
    {
        $request->validate([
            'search_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'threshold' => 'nullable|numeric|min:0|max:1'
        ]);

        $threshold = $request->get('threshold', 0.8);

        try {
            // Clean up any previous search images for this session before storing new one
            $this->cleanupPreviousSearchImages();

            // Generate embedding for the search image
            $searchEmbedding = $this->clipService->generateEmbedding($request->file('search_image'));
            
            if (!$searchEmbedding) {
                return back()->with('error', 'Failed to process the uploaded image. Please try again.');
            }

            Log::info('Generated search embedding', ['embedding_size' => count($searchEmbedding)]);

            // Find similar posts with pagination
            $similarPosts = $this->clipService->findSimilarPostsPaginated($searchEmbedding, $threshold, $request);

            Log::info('Found similar posts', ['count' => $similarPosts->total()]);

            // Store the search image temporarily for display with session-specific naming
            $sessionId = $request->session()->getId();
            $extension = $request->file('search_image')->getClientOriginalExtension();
            $filename = 'search_' . $sessionId . '_' . time() . '.' . $extension;
            $searchImagePath = $request->file('search_image')->storeAs('temp/search', $filename, 'public');

            // Store the current search image path in session for cleanup
            $request->session()->put('current_search_image', $searchImagePath);

            return view('ai-search.results', [
                'similarPosts' => $similarPosts,
                'searchImagePath' => $searchImagePath,
                'threshold' => $threshold,
                'totalFound' => $similarPosts->total()
            ]);

        } catch (\Exception $e) {
            Log::error('AI Image Search Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'An error occurred while searching. Please try again.');
        }
    }

    /**
     * Clean up previous search images for the current session
     */
    private function cleanupPreviousSearchImages()
    {
        try {
            $sessionId = session()->getId();
            $disk = Storage::disk('public');
            
            // Get current search image from session and delete it
            $currentSearchImage = session('current_search_image');
            if ($currentSearchImage && $disk->exists($currentSearchImage)) {
                $disk->delete($currentSearchImage);
                Log::info('Cleaned up previous search image', ['file' => $currentSearchImage]);
            }

            // Also clean up any orphaned files for this session (fallback cleanup)
            $tempSearchPath = 'temp/search';
            if ($disk->exists($tempSearchPath)) {
                $files = $disk->files($tempSearchPath);
                foreach ($files as $file) {
                    $filename = basename($file);
                    // Check if file belongs to current session
                    if (Str::contains($filename, 'search_' . $sessionId . '_')) {
                        $disk->delete($file);
                        Log::info('Cleaned up orphaned search image', ['file' => $file]);
                    }
                }
            }

            // Clear the session variable
            session()->forget('current_search_image');

        } catch (\Exception $e) {
            Log::warning('Error during search image cleanup', [
                'error' => $e->getMessage()
            ]);
        }
    }

    }