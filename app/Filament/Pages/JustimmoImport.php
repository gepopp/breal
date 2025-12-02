<?php

namespace App\Filament\Pages;

use App\Jobs\ImportRealtyJob;
use App\Justimmo\Importer;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class JustimmoImport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';

    protected static string $view = 'filament.pages.justimmo-import';

    protected static ?string $navigationLabel = 'Justimmo Import';

    protected static ?string $title = 'Justimmo Import - Schritt für Schritt';

    protected static ?string $navigationGroup = 'Import';

    // Only allow admin users to see and access this page
    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->admin;
    }

    public static function shouldRegisterNavigation(): bool
    {
        // Only show in navigation for admin users
        return static::canAccess();
    }

    public ?array $zipFileInfo = null;
    public ?array $extractedXmlInfo = null;
    public ?array $batchFilesInfo = null;
    public ?int $propertiesCount = null;
    public bool $tableCleared = false;
    public bool $importCompleted = false;

    public function mount(): void
    {
        $this->checkZipFile();
    }

    public function checkZipFile(): void
    {
        $zipFilePath = 'imports/openimmo.zip';
        $fullZipPath = storage_path('app/public/' . $zipFilePath);

        if (file_exists($fullZipPath)) {
            $this->zipFileInfo = [
                'path' => $zipFilePath,
                'filename' => 'openimmo.zip',
                'modified' => filemtime($fullZipPath),
                'modified_date' => date('Y-m-d H:i:s', filemtime($fullZipPath)),
                'size' => filesize($fullZipPath),
                'size_formatted' => round(filesize($fullZipPath) / 1024 / 1024, 2) . ' MB'
            ];
        } else {
            $this->zipFileInfo = null;
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->label('Aktualisieren')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    $this->reset();
                    $this->checkZipFile();

                    Notification::make()
                        ->title('Status aktualisiert')
                        ->success()
                        ->send();
                }),
        ];
    }

    public function extractXmlAction(): void
    {
        if (!$this->zipFileInfo) {
            Notification::make()
                ->title('Fehler: Keine ZIP-Datei gefunden')
                ->danger()
                ->send();
            return;
        }

        try {
            $importer = new Importer();
            $extractedPath = $importer->extractZipFile($this->zipFileInfo['path']);

            $fullXmlPath = storage_path('app/public/' . $extractedPath);
            $xmlContent = file_get_contents($fullXmlPath);
            $fileSize = filesize($fullXmlPath);

            $this->extractedXmlInfo = [
                'path' => $extractedPath,
                'size' => $fileSize,
                'size_formatted' => round($fileSize / 1024, 2) . ' KB',
                'preview' => substr($xmlContent, 0, 500)
            ];

            Notification::make()
                ->title('XML erfolgreich extrahiert')
                ->success()
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Fehler beim Extrahieren: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function extractPropertiesAction(): void
    {
        if (!$this->extractedXmlInfo) {
            Notification::make()
                ->title('Fehler: Zuerst XML extrahieren')
                ->danger()
                ->send();
            return;
        }

        try {
            $importer = new Importer();
            $importer->extractBatchFiles($this->extractedXmlInfo['path']);

            $batchFiles = $importer->getSortedFilesWithFileFacade('batches');

            $this->batchFilesInfo = [
                'count' => count($batchFiles),
                'sample' => array_slice($batchFiles, 0, 5)
            ];

            $this->propertiesCount = count($batchFiles);

            Notification::make()
                ->title('Immobilien-Dateien extrahiert: ' . count($batchFiles))
                ->success()
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Fehler beim Extrahieren: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function truncateTableAction(): void
    {
        try {
            DB::table('realties')->truncate();
            $this->tableCleared = true;

            Notification::make()
                ->title('Datenbank geleert')
                ->success()
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Fehler beim Leeren: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function importPropertiesAction(): void
    {
        if (!$this->tableCleared) {
            Notification::make()
                ->title('Fehler: Zuerst Datenbank leeren')
                ->danger()
                ->send();
            return;
        }

        if (!$this->batchFilesInfo) {
            Notification::make()
                ->title('Fehler: Keine Immobilien-Dateien vorhanden')
                ->danger()
                ->send();
            return;
        }

        try {
            $importer = new Importer();
            $files = $importer->getSortedFilesWithFileFacade('batches');

            foreach ($files as $file) {
                ImportRealtyJob::dispatch($file['path']);
            }

            $this->importCompleted = true;

            Notification::make()
                ->title('Import gestartet! ' . count($files) . ' Objekte werden importiert')
                ->success()
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Fehler beim Import: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function cleanupAction(): void
    {
        try {
            $importer = new Importer();

            // Delete batch files
            $batchFiles = $importer->getSortedFilesWithFileFacade('batches');
            $deletedCount = 0;

            foreach ($batchFiles as $file) {
                $path = storage_path('app/public/' . $file['path']);
                if (file_exists($path)) {
                    unlink($path);
                    $deletedCount++;
                }
            }

            // Delete import XMLs (but not ZIPs)
            $importsFiles = $importer->getSortedFilesWithFileFacade('imports');
            foreach ($importsFiles as $file) {
                if (str_contains($file['filename'], '.xml')) {
                    $path = storage_path('app/public/' . $file['path']);
                    if (file_exists($path)) {
                        unlink($path);
                        $deletedCount++;
                    }
                }
            }

            // Delete the zip file
            $zipFilePath = storage_path('app/public/imports/openimmo.zip');
            if (file_exists($zipFilePath)) {
                unlink($zipFilePath);
                $deletedCount++;
            }

            $this->reset();
            $this->checkZipFile();

            Notification::make()
                ->title('Aufräumen abgeschlossen: ' . $deletedCount . ' Dateien gelöscht')
                ->success()
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Fehler beim Aufräumen: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}
