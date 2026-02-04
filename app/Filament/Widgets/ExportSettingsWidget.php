<?php

namespace App\Filament\Widgets;

use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class ExportSettingsWidget extends Widget
{
    protected string $view = 'filament.widgets.export-settings-widget';

    protected int|string|array $columnSpan = 'full';

    public function export()
    {
        try {
            // Define the file path (relative to storage/app)
            $fileName = 'settings-export-'.now()->format('Y-m-d_His').'.json';
            $filePath = 'exports/'.$fileName;

            // Run the artisan command with relative path
            $exitCode = Artisan::call('settings:export', [
                '--file' => $filePath,
                '--disk' => 'local',
            ]);

            // Check if command succeeded
            if ($exitCode !== 0) {
                Notification::make()
                    ->title('Export failed')
                    ->body('The artisan command failed. Exit code: '.$exitCode)
                    ->danger()
                    ->send();

                return;
            }

            // Check if file was created
            if (! Storage::disk('local')->exists($filePath)) {
                Notification::make()
                    ->title('Export failed')
                    ->body('The settings export file could not be created.')
                    ->danger()
                    ->send();

                return;
            }

            // Send success notification
            Notification::make()
                ->title('Export successful')
                ->body('Settings have been exported successfully.')
                ->success()
                ->send();

            // Trigger download via JavaScript
            $this->dispatch('download-file', [
                'url' => route('download-settings-export', ['filename' => $fileName]),
            ]);

        } catch (\Exception $e) {
            Notification::make()
                ->title('Export failed')
                ->body('Error: '.$e->getMessage())
                ->danger()
                ->send();
        }
    }
}
