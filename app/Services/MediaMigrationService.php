<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaMigrationService
{
    protected string $sourceDisk;
    protected string $targetDisk;
    protected int $migratedCount = 0;
    protected int $errorCount = 0;
    protected array $errors = [];
    protected $outputCallback;

    public function __construct(string $sourceDisk = 'public', string $targetDisk = 's3')
    {
        $this->sourceDisk = $sourceDisk;
        $this->targetDisk = $targetDisk;
    }

    /**
     * Set callback for output messages
     */
    public function setOutputCallback(callable $callback): self
    {
        $this->outputCallback = $callback;
        return $this;
    }

    /**
     * Output a message via callback or log
     */
    protected function output(string $message, string $level = 'info'): void
    {
        if ($this->outputCallback) {
            call_user_func($this->outputCallback, $message, $level);
        }
        Log::{$level}($message);
    }

    /**
     * Migrate all media files from source disk to target disk
     */
    public function migrateAll(bool $deleteOldFiles = false): array
    {
        $this->migratedCount = 0;
        $this->errorCount = 0;
        $this->errors = [];

        $media = Media::where('disk', $this->sourceDisk)->get();

        $this->output("Starting media migration: {$media->count()} files to migrate from {$this->sourceDisk} to {$this->targetDisk}");

        foreach ($media as $mediaItem) {
            try {
                $this->output("\n[Media ID: {$mediaItem->id}] Processing: {$mediaItem->file_name}");
                $this->migrateMediaItem($mediaItem, $deleteOldFiles);
                $this->migratedCount++;
                $this->output("[Media ID: {$mediaItem->id}] ✓ Successfully migrated", 'info');
            } catch (\Exception $e) {
                $this->errorCount++;
                $this->errors[] = [
                    'media_id' => $mediaItem->id,
                    'file_name' => $mediaItem->file_name,
                    'error' => $e->getMessage(),
                ];
                $this->output("[Media ID: {$mediaItem->id}] ✗ Failed: {$e->getMessage()}", 'error');
            }
        }

        $this->output("\nMedia migration completed. Migrated: {$this->migratedCount}, Errors: {$this->errorCount}");

        return [
            'total' => $media->count(),
            'migrated' => $this->migratedCount,
            'errors' => $this->errorCount,
            'error_details' => $this->errors,
        ];
    }

    /**
     * Migrate a single media item
     */
    protected function migrateMediaItem(Media $media, bool $deleteOldFiles = false): void
    {
        DB::beginTransaction();

        try {
            // Store old paths before updating database (important!)
            $oldPaths = [
                'original' => $media->getPath(),
                'conversions' => [],
                'responsive' => [],
            ];

            // Store conversion paths
            foreach ($media->getGeneratedConversions() as $conversionName => $generated) {
                if ($generated) {
                    $oldPaths['conversions'][$conversionName] = $media->getPath($conversionName);
                }
            }

            // Migrate original file
            $this->output("  → Migrating original file...");
            $this->migrateFile($media->getPath(), $media->getPath(), $media);

            // Migrate conversions
            if (!empty($oldPaths['conversions'])) {
                $this->output("  → Migrating " . count($oldPaths['conversions']) . " conversion(s)...");
                foreach ($oldPaths['conversions'] as $conversionName => $conversionPath) {
                    $this->migrateFile($conversionPath, $conversionPath, $media, $conversionName);
                }
            }

            // Migrate responsive images
            $responsiveCount = $this->migrateResponsiveImages($media);
            if ($responsiveCount > 0) {
                $this->output("  → Migrated {$responsiveCount} responsive image(s)");
            }

            // Update database record
            $this->output("  → Updating database record...");
            $media->update([
                'disk' => $this->targetDisk,
                'conversions_disk' => $this->targetDisk,
            ]);

            // Delete old files if requested
            if ($deleteOldFiles) {
                $this->output("  → Deleting old files from source disk...");
                $this->deleteOldFilesWithPaths($oldPaths);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Copy a file from source disk to target disk
     */
    protected function migrateFile(string $sourcePath, string $targetPath, ?Media $media = null, ?string $conversionName = null): void
    {
        // Remove leading slash if present
        $sourcePath = ltrim($sourcePath, '/');
        $targetPath = ltrim($targetPath, '/');

        $fileType = $conversionName ? "conversion '{$conversionName}'" : 'original';

        if (!Storage::disk($this->sourceDisk)->exists($sourcePath)) {
            $this->output("    ⚠ Source file does not exist ({$fileType}): {$sourcePath}", 'warning');
            return;
        }

        // Get file size for reporting
        $fileSize = Storage::disk($this->sourceDisk)->size($sourcePath);
        $fileSizeFormatted = $this->formatBytes($fileSize);

        // Download from source
        $this->output("    ↓ Downloading {$fileType} ({$fileSizeFormatted}): {$sourcePath}");
        $fileContents = Storage::disk($this->sourceDisk)->get($sourcePath);

        // Upload to target disk
        $this->output("    ↑ Uploading to {$this->targetDisk}: {$targetPath}");
        Storage::disk($this->targetDisk)->put($targetPath, $fileContents, 'public');

        // Verify upload
        if (Storage::disk($this->targetDisk)->exists($targetPath)) {
            $this->output("    ✓ Upload verified", 'debug');
        } else {
            throw new \Exception("Upload verification failed for: {$targetPath}");
        }
    }

    /**
     * Format bytes to human readable size
     */
    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    /**
     * Migrate responsive images
     */
    protected function migrateResponsiveImages(Media $media): int
    {
        $responsiveImages = $media->responsive_images;
        $count = 0;

        if (empty($responsiveImages)) {
            return 0;
        }

        foreach ($responsiveImages as $conversionName => $responsiveImageData) {
            if (!isset($responsiveImageData['urls'])) {
                continue;
            }

            // Get the base path for this conversion
            $basePath = $media->getPath($conversionName === 'media_library_original' ? '' : $conversionName);
            $directory = dirname($basePath);

            // Migrate each responsive image file
            foreach ($responsiveImageData['urls'] as $url) {
                // Extract filename from URL
                $filename = basename(parse_url($url, PHP_URL_PATH));
                $filePath = $directory . '/' . $filename;

                try {
                    $this->migrateFile($filePath, $filePath, $media, "responsive-{$conversionName}");
                    $count++;
                } catch (\Exception $e) {
                    $this->output("    ⚠ Failed to migrate responsive image {$filePath}: {$e->getMessage()}", 'warning');
                }
            }
        }

        return $count;
    }

    /**
     * Delete old files from source disk using stored paths
     */
    protected function deleteOldFilesWithPaths(array $oldPaths): void
    {
        // Delete original file
        $originalPath = ltrim($oldPaths['original'], '/');
        if (Storage::disk($this->sourceDisk)->exists($originalPath)) {
            Storage::disk($this->sourceDisk)->delete($originalPath);
            $this->output("    🗑 Deleted original from source disk");
        }

        // Delete conversions
        foreach ($oldPaths['conversions'] as $conversionName => $conversionPath) {
            $path = ltrim($conversionPath, '/');
            if (Storage::disk($this->sourceDisk)->exists($path)) {
                Storage::disk($this->sourceDisk)->delete($path);
                $this->output("    🗑 Deleted conversion '{$conversionName}' from source disk");
            }
        }

        // Try to delete empty directories
        $this->deleteEmptyDirectories($originalPath);
    }

    /**
     * Delete old files from source disk (legacy method, kept for compatibility)
     */
    protected function deleteOldFiles(Media $media): void
    {
        // Delete original file
        $originalPath = ltrim($media->getPath(), '/');
        if (Storage::disk($this->sourceDisk)->exists($originalPath)) {
            Storage::disk($this->sourceDisk)->delete($originalPath);
        }

        // Delete conversions
        foreach ($media->getGeneratedConversions() as $conversionName => $generated) {
            if ($generated) {
                $conversionPath = ltrim($media->getPath($conversionName), '/');
                if (Storage::disk($this->sourceDisk)->exists($conversionPath)) {
                    Storage::disk($this->sourceDisk)->delete($conversionPath);
                }
            }
        }

        // Delete responsive images
        $this->deleteResponsiveImages($media);

        // Try to delete empty directories
        $this->deleteEmptyDirectories($originalPath);
    }

    /**
     * Delete responsive images from source disk
     */
    protected function deleteResponsiveImages(Media $media): void
    {
        $responsiveImages = $media->responsive_images;

        if (empty($responsiveImages)) {
            return;
        }

        foreach ($responsiveImages as $conversionName => $responsiveImageData) {
            if (!isset($responsiveImageData['urls'])) {
                continue;
            }

            $basePath = $media->getPath($conversionName === 'media_library_original' ? '' : $conversionName);
            $directory = dirname($basePath);

            foreach ($responsiveImageData['urls'] as $url) {
                $filename = basename(parse_url($url, PHP_URL_PATH));
                $filePath = $directory . '/' . $filename;
                $filePath = ltrim($filePath, '/');

                if (Storage::disk($this->sourceDisk)->exists($filePath)) {
                    Storage::disk($this->sourceDisk)->delete($filePath);
                }
            }
        }
    }

    /**
     * Delete empty directories after file migration
     */
    protected function deleteEmptyDirectories(string $path): void
    {
        $path = ltrim($path, '/');
        $directory = dirname($path);

        // Try to delete the directory and parent directories if empty
        while ($directory && $directory !== '.') {
            $files = Storage::disk($this->sourceDisk)->files($directory);
            $directories = Storage::disk($this->sourceDisk)->directories($directory);

            if (empty($files) && empty($directories)) {
                Storage::disk($this->sourceDisk)->deleteDirectory($directory);
                $this->output("    🗑 Deleted empty directory: {$directory}");
                $directory = dirname($directory);
            } else {
                break;
            }
        }
    }

    /**
     * Get migration statistics
     */
    public function getStats(): array
    {
        return [
            'migrated' => $this->migratedCount,
            'errors' => $this->errorCount,
            'error_details' => $this->errors,
        ];
    }
}
