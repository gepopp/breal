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
    public function migrateAll(bool $deleteOldFiles = false, bool $testMode = false): array
    {
        $this->migratedCount = 0;
        $this->errorCount = 0;
        $this->errors = [];

        // Validate disk configuration
        $this->validateDiskConfiguration();

        $media = Media::where('disk', $this->sourceDisk)->get();

        $modeText = $testMode ? ' [TEST MODE - No database changes]' : '';
        $this->output("Starting media migration{$modeText}: {$media->count()} files to migrate from {$this->sourceDisk} to {$this->targetDisk}");

        foreach ($media as $mediaItem) {
            try {
                $this->output("\n[Media ID: {$mediaItem->id}] Processing: {$mediaItem->file_name}");
                $this->migrateMediaItem($mediaItem, $deleteOldFiles, $testMode);
                $this->migratedCount++;
                $successMsg = $testMode ? '✓ Files uploaded (DB not updated)' : '✓ Successfully migrated';
                $this->output("[Media ID: {$mediaItem->id}] {$successMsg}", 'info');
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
            'test_mode' => $testMode,
        ];
    }

    /**
     * Migrate a single media item
     */
    protected function migrateMediaItem(Media $media, bool $deleteOldFiles = false, bool $testMode = false): void
    {
        if (!$testMode) {
            DB::beginTransaction();
        }

        try {
            // Get the relative path from the disk root (e.g., "206/filename.jpg")
            // Spatie stores files as: {media_id}/{file_name}
            $relativePath = $media->id . '/' . $media->file_name;

            // Migrate original file only
            $this->output("  → Migrating original file...");
            $this->migrateFile($relativePath, $relativePath, $media);

            // Update database record (skip in test mode)
            if ($testMode) {
                $this->output("  → [TEST MODE] Skipping database update", 'warning');
            } else {
                $this->output("  → Updating database record...");
                $media->update([
                    'disk' => $this->targetDisk,
                    'conversions_disk' => $this->targetDisk,
                ]);
            }

            // Delete old file if requested (skip in test mode)
            if ($deleteOldFiles && !$testMode) {
                $this->output("  → Deleting old file from source disk...");
                $this->deleteOldFile($relativePath);
            } elseif ($deleteOldFiles && $testMode) {
                $this->output("  → [TEST MODE] Would delete old file from source disk", 'warning');
            }

            if (!$testMode) {
                DB::commit();
            }
        } catch (\Exception $e) {
            if (!$testMode) {
                DB::rollBack();
            }
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

        // Get URL for the file - use Media's getUrl() method if media object is provided
        if ($media) {
            $fileUrl = $media->getUrl();
        } else {
            $fileUrl = Storage::disk($this->sourceDisk)->url($sourcePath);
        }

        $this->output("    📍 Source path: {$sourcePath}");
        $this->output("    🌐 Download URL: {$fileUrl}");

        // Check if file exists
        if (!Storage::disk($this->sourceDisk)->exists($sourcePath)) {
            throw new \Exception("Source file does not exist: {$sourcePath}");
        }

        // Get file size for reporting
        $fileSize = Storage::disk($this->sourceDisk)->size($sourcePath);
        $fileSizeFormatted = $this->formatBytes($fileSize);

        // Download from source via URL
        $this->output("    ↓ Downloading file ({$fileSizeFormatted})...");

        try {
            $fileContents = @file_get_contents($fileUrl);

            if ($fileContents === false) {
                $error = error_get_last();
                throw new \Exception("Failed to download file: " . ($error['message'] ?? 'Unknown error'));
            }

            $downloadedSize = strlen($fileContents);

            if (empty($fileContents)) {
                throw new \Exception("Downloaded file is empty");
            }

            $this->output("    ✓ Downloaded successfully: " . $this->formatBytes($downloadedSize));

            // Upload to target disk
            $this->output("    ↑ Uploading to {$this->targetDisk} disk at path: {$targetPath}");

            try {
                // Get disk configuration for debugging
                $diskConfig = config("filesystems.disks.{$this->targetDisk}");
                $this->output("    📋 Target disk driver: " . ($diskConfig['driver'] ?? 'unknown'));

                if (isset($diskConfig['bucket'])) {
                    $this->output("    📦 S3 Bucket: " . $diskConfig['bucket']);
                }
                if (isset($diskConfig['region'])) {
                    $this->output("    🌍 S3 Region: " . $diskConfig['region']);
                }

                $uploadResult = Storage::disk($this->targetDisk)->put($targetPath, $fileContents, 'public');

                if ($uploadResult === false) {
                    throw new \Exception("Storage::put() returned false - check S3 credentials and permissions");
                }

                $this->output("    ✓ Storage::put() returned success");

            } catch (\Exception $uploadException) {
                // Detailed error output
                $this->output("    ✗ Upload failed!", 'error');
                $this->output("    ✗ Error type: " . get_class($uploadException), 'error');
                $this->output("    ✗ Error message: " . $uploadException->getMessage(), 'error');
                $this->output("    ✗ Error code: " . $uploadException->getCode(), 'error');

                // Check for AWS-specific errors
                if (method_exists($uploadException, 'getAwsErrorCode')) {
                    $this->output("    ✗ AWS Error Code: " . $uploadException->getAwsErrorCode(), 'error');
                }

                if (method_exists($uploadException, 'getStatusCode')) {
                    $this->output("    ✗ HTTP Status Code: " . $uploadException->getStatusCode(), 'error');
                }

                // Show stack trace for debugging
                $this->output("    ✗ Stack trace:", 'error');
                $trace = $uploadException->getTraceAsString();
                $traceLines = explode("\n", $trace);
                foreach (array_slice($traceLines, 0, 5) as $line) {
                    $this->output("      " . $line, 'error');
                }

                throw new \Exception("Failed to upload to S3: " . $uploadException->getMessage());
            }

            // Verify upload
            $this->output("    🔍 Verifying upload...");

            if (!Storage::disk($this->targetDisk)->exists($targetPath)) {
                throw new \Exception("Upload verification failed - file does not exist on target disk");
            }

            $uploadedSize = Storage::disk($this->targetDisk)->size($targetPath);

            if ($uploadedSize !== $downloadedSize) {
                throw new \Exception("Size mismatch - expected {$downloadedSize} bytes, got {$uploadedSize} bytes");
            }

            $this->output("    ✓✓ UPLOAD SUCCESSFUL - File verified on {$this->targetDisk} ({$fileSizeFormatted})");

        } catch (\Exception $e) {
            $this->output("    ✗✗ UPLOAD FAILED: {$e->getMessage()}", 'error');
            throw $e;
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
     * Delete a single file from source disk
     */
    protected function deleteOldFile(string $filePath): void
    {
        $filePath = ltrim($filePath, '/');

        if (Storage::disk($this->sourceDisk)->exists($filePath)) {
            Storage::disk($this->sourceDisk)->delete($filePath);
            $this->output("    🗑 Deleted original from source disk");

            // Try to delete empty directories
            $this->deleteEmptyDirectories($filePath);
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

    /**
     * Validate disk configuration before migration
     */
    protected function validateDiskConfiguration(): void
    {
        $this->output("\n🔧 Validating disk configuration...");

        // Check source disk
        $sourceConfig = config("filesystems.disks.{$this->sourceDisk}");
        if (!$sourceConfig) {
            throw new \Exception("Source disk '{$this->sourceDisk}' is not configured in config/filesystems.php");
        }
        $this->output("  ✓ Source disk '{$this->sourceDisk}' configured (driver: {$sourceConfig['driver']})");

        // Check target disk
        $targetConfig = config("filesystems.disks.{$this->targetDisk}");
        if (!$targetConfig) {
            throw new \Exception("Target disk '{$this->targetDisk}' is not configured in config/filesystems.php");
        }
        $this->output("  ✓ Target disk '{$this->targetDisk}' configured (driver: {$targetConfig['driver']})");

        // Validate S3 configuration if target is S3
        if ($targetConfig['driver'] === 's3') {
            $this->output("\n  📦 S3 Configuration:");

            $missingConfig = [];

            if (empty($targetConfig['key'])) {
                $missingConfig[] = 'AWS_ACCESS_KEY_ID';
            } else {
                $keyPreview = substr($targetConfig['key'], 0, 4) . '...' . substr($targetConfig['key'], -4);
                $this->output("    • Access Key: {$keyPreview}");
            }

            if (empty($targetConfig['secret'])) {
                $missingConfig[] = 'AWS_SECRET_ACCESS_KEY';
            } else {
                $this->output("    • Secret Key: [CONFIGURED]");
            }

            if (empty($targetConfig['region'])) {
                $missingConfig[] = 'AWS_DEFAULT_REGION';
            } else {
                $this->output("    • Region: {$targetConfig['region']}");
            }

            if (empty($targetConfig['bucket'])) {
                $missingConfig[] = 'AWS_BUCKET';
            } else {
                $this->output("    • Bucket: {$targetConfig['bucket']}");
            }

            if (!empty($missingConfig)) {
                throw new \Exception("Missing S3 configuration in .env: " . implode(', ', $missingConfig));
            }

            // Test S3 connectivity
            $this->output("\n  🔌 Testing S3 connection...");
            try {
                // Try to check if bucket exists by attempting a simple operation
                Storage::disk($this->targetDisk)->exists('__migration_test__');
                $this->output("  ✓ S3 connection successful!");
            } catch (\Exception $e) {
                $this->output("  ✗ S3 connection failed!", 'error');
                $this->output("  ✗ Error: " . $e->getMessage(), 'error');
                throw new \Exception("Cannot connect to S3. Please verify your credentials and bucket configuration.");
            }
        }

        $this->output("\n✓ All disk configurations validated successfully!\n");
    }
}
