<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Spatie\LaravelSettings\Settings;

class ExportSettings extends Command
{
    protected $signature = 'settings:export {--file=storage/app/settings-export.json}';

    protected $description = 'Export translatable text from all Spatie settings to a JSON file';

    public function handle()
    {
        $this->info('Exporting settings...');

        $settingsData = [];
        $settingsClasses = $this->discoverSettingsClasses();

        foreach ($settingsClasses as $settingsClass) {
            $this->line("Processing: {$settingsClass}");

            try {
                $settings = app($settingsClass);
                $translatable = $settings->toArray();

                // Only include settings that have translatable content
                if (!empty($translatable)) {
                    $settingsData[$settingsClass] = [
                        'group' => $settings::group(),
                        'properties' => $translatable,
                    ];
                }
            } catch (\Exception $e) {
                $this->error("Failed to load {$settingsClass}: " . $e->getMessage());
                continue;
            }
        }

        $filePath = $this->option('file');

        // Ensure directory exists
        $directory = dirname($filePath);
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // Convert to pretty JSON
        $json = json_encode($settingsData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        File::put($filePath, $json);

        $this->info("Settings exported successfully to: {$filePath}");
        $this->info("Total settings classes exported: " . count($settingsData));

        return Command::SUCCESS;
    }

    protected function discoverSettingsClasses(): array
    {
        $settingsClasses = [];

        // Get explicitly registered settings
        $registeredSettings = config('settings.settings', []);
        foreach ($registeredSettings as $settingsClass) {
            if (class_exists($settingsClass)) {
                $settingsClasses[] = $settingsClass;
            }
        }

        // Auto-discover settings from configured paths
        $discoveryPaths = config('settings.auto_discover_settings', []);
        foreach ($discoveryPaths as $path) {
            if (File::exists($path)) {
                $files = File::allFiles($path);
                foreach ($files as $file) {
                    $className = $this->getClassNameFromFile($file->getPathname(), $path);
                    if ($className && class_exists($className) && is_subclass_of($className, Settings::class)) {
                        $settingsClasses[] = $className;
                    }
                }
            }
        }

        return array_unique($settingsClasses);
    }

    protected function getClassNameFromFile(string $filePath, string $basePath): ?string
    {
        $relativePath = str_replace($basePath, '', $filePath);
        $relativePath = str_replace('.php', '', $relativePath);
        $relativePath = trim($relativePath, '/');

        // Convert path to namespace
        $namespace = 'App\\' . str_replace('/', '\\', dirname($relativePath));
        if ($namespace === 'App\\.') {
            $namespace = 'App';
        }

        $className = basename($relativePath);
        $fullClassName = $namespace . '\\' . $className;

        return $fullClassName;
    }
}
