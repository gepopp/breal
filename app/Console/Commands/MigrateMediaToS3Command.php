<?php

namespace App\Console\Commands;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Services\MediaMigrationService;
use Illuminate\Console\Command;

class MigrateMediaToS3Command extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:migrate-to-s3
                            {--source=public : The source disk to migrate from}
                            {--target=s3 : The target disk to migrate to}
                            {--delete : Delete old files from source disk after migration}
                            {--test-upload : Upload files to S3 without updating database (for testing)}
                            {--dry-run : Show what would be migrated without actually doing it}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate media files from public disk to S3 and update database records';

    /**
     * Execute the console command.
     */
    public function handle(MediaMigrationService $migrationService): int
    {
        $sourceDisk = $this->option('source');
        $targetDisk = $this->option('target');
        $deleteOldFiles = $this->option('delete');
        $dryRun = $this->option('dry-run');
        $testUpload = $this->option('test-upload');

        $this->info("Media Migration from {$sourceDisk} to {$targetDisk}");
        $this->info('----------------------------------------');

        if ($dryRun) {
            $this->warn('DRY RUN MODE - No changes will be made');
            $this->showMigrationPreview($sourceDisk, $targetDisk);
            return self::SUCCESS;
        }

        if ($testUpload) {
            $this->warn('TEST UPLOAD MODE - Files will be uploaded but database will NOT be updated');
            $this->newLine();
        }

        // Confirm before proceeding
        $confirmMessage = $testUpload
            ? "This will upload all media files to {$targetDisk} WITHOUT updating the database. Continue?"
            : "This will migrate all media files from {$sourceDisk} to {$targetDisk}. Continue?";

        if (!$this->confirm($confirmMessage)) {
            $this->info('Migration cancelled.');
            return self::SUCCESS;
        }

        if ($deleteOldFiles && !$testUpload) {
            if (!$this->confirm('You have chosen to delete old files. Are you sure?', false)) {
                $this->info('Migration cancelled.');
                return self::SUCCESS;
            }
        }

        if ($deleteOldFiles && $testUpload) {
            $this->warn('Note: --delete flag is ignored in test upload mode');
        }

        // Run migration
        $migrationService = new MediaMigrationService($sourceDisk, $targetDisk);

        // Set up output callback to display messages in real-time
        $migrationService->setOutputCallback(function($message, $level) {
            match($level) {
                'error' => $this->error($message),
                'warning' => $this->warn($message),
                'debug' => $this->line($message),
                default => $this->info($message),
            };
        });

        $this->info('Starting migration...');
        $this->newLine();

        $result = $migrationService->migrateAll($deleteOldFiles, $testUpload);

        $this->newLine();

        // Display results
        if ($testUpload) {
            $this->info('Test upload completed!');
            $this->warn('Note: Database was NOT updated. Run without --test-upload to complete migration.');
        } else {
            $this->info('Migration completed!');
        }

        $this->table(
            ['Metric', 'Count'],
            [
                ['Total files', $result['total']],
                ['Successfully uploaded', $result['migrated']],
                ['Errors', $result['errors']],
            ]
        );

        // Display errors if any
        if ($result['errors'] > 0) {
            $this->error("\nErrors occurred during migration:");
            $this->table(
                ['Media ID', 'File Name', 'Error'],
                collect($result['error_details'])->map(fn($error) => [
                    $error['media_id'],
                    $error['file_name'],
                    $error['error'],
                ])->toArray()
            );
        }

        return $result['errors'] > 0 ? self::FAILURE : self::SUCCESS;
    }

    /**
     * Show what would be migrated in dry-run mode
     */
    protected function showMigrationPreview(string $sourceDisk, string $targetDisk): void
    {
        $media = Media::where('disk', $sourceDisk)->get();

        $this->info("\nFound {$media->count()} media files to migrate:");
        $this->newLine();

        $tableData = $media->take(10)->map(fn($item) => [
            $item->id,
            $item->collection_name,
            $item->file_name,
            $this->formatBytes($item->size),
            $item->disk,
            $targetDisk,
        ])->toArray();

        $this->table(
            ['ID', 'Collection', 'File Name', 'Size', 'Current Disk', 'Target Disk'],
            $tableData
        );

        if ($media->count() > 10) {
            $this->info("... and " . ($media->count() - 10) . " more files");
        }

        $this->newLine();
        $this->info("Total size: " . $this->formatBytes($media->sum('size')));
    }

    /**
     * Format bytes to human readable size
     */
    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
