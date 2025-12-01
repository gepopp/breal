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

    public function __construct(string $sourceDisk = 'public', string $targetDisk = 's3')
    {
        $this->sourceDisk = $sourceDisk;
        $this->targetDisk = $targetDisk;
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

        Log::info("Starting media migration: {$media->count()} files to migrate from {$this->sourceDisk} to {$this->targetDisk}");

        foreach ($media as $mediaItem) {
            try {
                $this->migrateMediaItem($mediaItem, $deleteOldFiles);
                $this->migratedCount++;
            } catch (\Exception $e) {
                $this->errorCount++;
                $this->errors[] = [
                    'media_id' => $mediaItem->id,
                    'file_name' => $mediaItem->file_name,
                    'error' => $e->getMessage(),
                ];
                Log::error("Failed to migrate media {$mediaItem->id}: {$e->getMessage()}");
            }
        }

        Log::info("Media migration completed. Migrated: {$this->migratedCount}, Errors: {$this->errorCount}");

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
            // Migrate original file
            $this->migrateFile($media->getPath(), $media->getPath());

            // Migrate conversions
            foreach ($media->getGeneratedConversions() as $conversionName => $generated) {
                if ($generated) {
                    $conversionPath = $media->getPath($conversionName);
                    $this->migrateFile($conversionPath, $conversionPath);
                }
            }

            // Migrate responsive images
            $this->migrateResponsiveImages($media);

            // Update database record
            $media->update([
                'disk' => $this->targetDisk,
                'conversions_disk' => $this->targetDisk,
            ]);

            // Delete old files if requested
            if ($deleteOldFiles) {
                $this->deleteOldFiles($media);
            }

            DB::commit();

            Log::info("Successfully migrated media {$media->id}: {$media->file_name}");
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Copy a file from source disk to target disk
     */
    protected function migrateFile(string $sourcePath, string $targetPath): void
    {
        // Remove leading slash if present
        $sourcePath = ltrim($sourcePath, '/');
        $targetPath = ltrim($targetPath, '/');

        if (!Storage::disk($this->sourceDisk)->exists($sourcePath)) {
            Log::warning("Source file does not exist: {$sourcePath}");
            return;
        }

        // Get file contents from source
        $fileContents = Storage::disk($this->sourceDisk)->get($sourcePath);

        // Write to target disk
        Storage::disk($this->targetDisk)->put($targetPath, $fileContents, 'public');

        Log::debug("Migrated file: {$sourcePath} -> {$targetPath}");
    }

    /**
     * Migrate responsive images
     */
    protected function migrateResponsiveImages(Media $media): void
    {
        $responsiveImages = $media->responsive_images;

        if (empty($responsiveImages)) {
            return;
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
                    $this->migrateFile($filePath, $filePath);
                } catch (\Exception $e) {
                    Log::warning("Failed to migrate responsive image {$filePath}: {$e->getMessage()}");
                }
            }
        }
    }

    /**
     * Delete old files from source disk
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
        $this->deleteEmptyDirectories($media);
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
    protected function deleteEmptyDirectories(Media $media): void
    {
        $path = ltrim($media->getPath(), '/');
        $directory = dirname($path);

        // Try to delete the directory and parent directories if empty
        while ($directory && $directory !== '.') {
            $files = Storage::disk($this->sourceDisk)->files($directory);
            $directories = Storage::disk($this->sourceDisk)->directories($directory);

            if (empty($files) && empty($directories)) {
                Storage::disk($this->sourceDisk)->deleteDirectory($directory);
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
