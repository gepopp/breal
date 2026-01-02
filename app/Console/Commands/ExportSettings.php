<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use ReflectionClass;
use ReflectionProperty;
use Spatie\LaravelSettings\Settings;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ExportSettings extends Command
{
    protected $signature = 'settings:export {--file=storage/app/settings-export.json} {--with-media-paths}';

    protected $description = 'Export all Spatie settings to a JSON file';

    public function handle()
    {
        $this->info('Exporting settings...');

        $settingsData = [];
        $settingsClasses = $this->discoverSettingsClasses();

        foreach ($settingsClasses as $settingsClass) {
            $this->line("Processing: {$settingsClass}");

            try {
                $settings = app($settingsClass);
                $settingsData[$settingsClass] = $this->extractSettings($settings);
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

    protected function extractSettings(Settings $settings): array
    {
        $reflection = new ReflectionClass($settings);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);

        $data = [
            'group' => $this->getSettingsGroup($settings),
            'properties' => [],
        ];

        foreach ($properties as $property) {
            // Skip static properties
            if ($property->isStatic()) {
                continue;
            }

            $propertyName = $property->getName();
            $value = $property->getValue($settings);

            // If with-media-paths option is enabled, resolve media IDs to paths
            if ($this->option('with-media-paths') && $this->looksLikeMediaIds($value)) {
                $value = $this->resolveMediaPaths($value);
            }

            $data['properties'][$propertyName] = $value;
        }

        return $data;
    }

    protected function getSettingsGroup(Settings $settings): ?string
    {
        $reflection = new ReflectionClass($settings);

        // Try to get the group from static property
        if ($reflection->hasProperty('group')) {
            $groupProperty = $reflection->getProperty('group');
            if ($groupProperty->isStatic()) {
                $groupProperty->setAccessible(true);
                return $groupProperty->getValue();
            }
        }

        return null;
    }

    protected function looksLikeMediaIds($value): bool
    {
        // Check if value is an integer or array of integers (potential media IDs)
        if (is_int($value)) {
            return true;
        }

        if (is_array($value) && !empty($value)) {
            foreach ($value as $item) {
                if (!is_int($item)) {
                    return false;
                }
            }
            return true;
        }

        return false;
    }

    protected function resolveMediaPaths($mediaIds): array
    {
        $ids = is_array($mediaIds) ? $mediaIds : [$mediaIds];

        $media = Media::whereIn('id', $ids)->get();

        return $media->map(function ($mediaItem) {
            return [
                'id' => $mediaItem->id,
                'file_name' => $mediaItem->file_name,
                'mime_type' => $mediaItem->mime_type,
                'url' => $mediaItem->getUrl(),
                'path' => $mediaItem->getPath(),
                'collection' => $mediaItem->collection_name,
            ];
        })->toArray();
    }
}
