<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CleanupOrphanedTempImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:orphaned-temp-images {--hours=2 : Number of hours after which temp images are considered orphaned}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up orphaned temporary search images that were not cleaned by session cleanup';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hours = (int) $this->option('hours');
        $cutoffTime = Carbon::now()->subHours($hours);
        
        $this->info("Cleaning up orphaned temporary search images older than {$hours} hours...");
        
        try {
            $disk = Storage::disk('public');
            $tempSearchPath = 'temp/search';
            
            // Check if the temp/search directory exists
            if (!$disk->exists($tempSearchPath)) {
                $this->info('No temp/search directory found. Nothing to clean up.');
                return 0;
            }
            
            // Get all files in the temp/search directory
            $files = $disk->allFiles($tempSearchPath);
            $deletedCount = 0;
            $totalSize = 0;
            
            foreach ($files as $file) {
                $filePath = $disk->path($file);
                
                // Check if file exists and get its modification time
                if (file_exists($filePath)) {
                    $fileModTime = Carbon::createFromTimestamp(filemtime($filePath));
                    
                    // If file is older than cutoff time, delete it
                    if ($fileModTime->lt($cutoffTime)) {
                        $fileSize = filesize($filePath);
                        $totalSize += $fileSize;
                        
                        if ($disk->delete($file)) {
                            $deletedCount++;
                            $this->line("Deleted orphaned file: {$file} (Modified: {$fileModTime->format('Y-m-d H:i:s')})");
                        } else {
                            $this->error("Failed to delete: {$file}");
                        }
                    }
                }
            }
            
            // Clean up empty directories
            $this->cleanupEmptyDirectories($disk, $tempSearchPath);
            
            $sizeInMB = round($totalSize / 1024 / 1024, 2);
            
            $this->info("Orphaned temp images cleanup completed!");
            $this->info("Files deleted: {$deletedCount}");
            $this->info("Space freed: {$sizeInMB} MB");
            
            // Log the cleanup activity
            Log::info('Orphaned temp images cleanup completed', [
                'files_deleted' => $deletedCount,
                'space_freed_mb' => $sizeInMB,
                'cutoff_hours' => $hours
            ]);
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("Error during cleanup: " . $e->getMessage());
            Log::error('Orphaned temp images cleanup failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }
    
    /**
     * Clean up empty directories recursively
     */
    private function cleanupEmptyDirectories($disk, $directory)
    {
        try {
            $directories = $disk->directories($directory);
            
            foreach ($directories as $dir) {
                // Recursively clean subdirectories first
                $this->cleanupEmptyDirectories($disk, $dir);
                
                // Check if directory is empty and delete it
                $files = $disk->allFiles($dir);
                $subdirs = $disk->directories($dir);
                
                if (empty($files) && empty($subdirs)) {
                    $disk->deleteDirectory($dir);
                    $this->line("Removed empty directory: {$dir}");
                }
            }
        } catch (\Exception $e) {
            $this->warn("Could not clean up directories: " . $e->getMessage());
        }
    }
}