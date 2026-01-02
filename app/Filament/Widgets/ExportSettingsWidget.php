<?php

namespace App\Filament\Widgets;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportSettingsWidget extends Widget implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    protected static string $view = 'filament.widgets.export-settings-widget';

    protected int | string | array $columnSpan = 'full';

    public function exportAction(): Action
    {
        return Action::make('export')
            ->label('Export Settings')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('primary')
            ->action(function () {
                try {
                    // Define the file path
                    $fileName = 'settings-export-' . now()->format('Y-m-d_His') . '.json';
                    $filePath = 'exports/' . $fileName;

                    // Run the artisan command
                    Artisan::call('settings:export', [
                        '--file' => storage_path('app/' . $filePath)
                    ]);

                    // Check if file was created
                    if (!Storage::exists($filePath)) {
                        Notification::make()
                            ->title('Export failed')
                            ->body('The settings export file could not be created.')
                            ->danger()
                            ->send();

                        return;
                    }

                    // Download the file
                    return response()->download(
                        storage_path('app/' . $filePath),
                        $fileName,
                        ['Content-Type' => 'application/json']
                    )->deleteFileAfterSend(true);

                } catch (\Exception $e) {
                    Notification::make()
                        ->title('Export failed')
                        ->body('Error: ' . $e->getMessage())
                        ->danger()
                        ->send();
                }
            });
    }
}
