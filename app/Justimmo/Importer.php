<?php

namespace App\Justimmo;

use App\Jobs\CleanupRealtiesJob;
use App\Jobs\ImportRealtyChunkJob;
use App\Models\Realty;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Importer
{
    public static function import()
    {
        $recipient = auth()->user();

        $instance = new self();

        // Look for zip files
        $zipFiles = $instance->getAllZipFiles();

        if (empty($zipFiles)) {
            if ($recipient) {
                Notification::make()
                    ->title('Keine neuen Dateien gefunden.')
                    ->sendToDatabase($recipient);
            }
            return;
        }

        // Sort by modification time (newest first)
        usort($zipFiles, function ($a, $b) {
            return $b['modified'] <=> $a['modified'];
        });

        // Delete all but the latest zip file
        if (count($zipFiles) > 1) {
            $instance->deleteOldZipFiles($zipFiles);
        }

        // Get the latest zip file
        $latestZipFile = $zipFiles[0];

        // Extract and process
        $path = $instance->extractZipFile($latestZipFile['path']);

        // Delete the processed zip file
        if (file_exists(storage_path('app/public/' . $latestZipFile['path']))) {
            unlink(storage_path('app/public/' . $latestZipFile['path']));
        }

        $instance->extractBatchFiles($path);

        // Delete the extracted XML file
        if (file_exists(storage_path('app/public/' . $path))) {
            unlink(storage_path('app/public/' . $path));
        }

        $files = $instance->getSortedFilesWithFileFacade('batches');

        $flattend = array_map(function ($value) {
            return $value['path'];
        }, $files);

        $chunks = array_chunk($flattend, 50);

        $jobs = [];
        foreach ($chunks as $chunk) {
            $jobs[] = new ImportRealtyChunkJob($chunk);
        }

        Bus::batch($jobs)->dispatch();

        CleanupRealtiesJob::dispatch();

        if ($recipient) {
            Notification::make()
                ->title('Import gestartet! Es werden ' . count($files) . ' Objekte aktualisiert!')
                ->sendToDatabase($recipient);
        }
    }

    /**
     * Get all zip files in the imports directory
     */
    public function getAllZipFiles(): array
    {
        $files = $this->getSortedFilesWithFileFacade('imports');
        $zipFiles = [];

        foreach ($files as $file) {
            if (str_contains($file['filename'], '.zip')) {
                $zipFiles[] = $file;
            }
        }

        return $zipFiles;
    }

    /**
     * Delete old zip files, keeping only the latest
     */
    public function deleteOldZipFiles(array $zipFiles): void
    {
        // Skip the first one (the latest), delete the rest
        for ($i = 1; $i < count($zipFiles); $i++) {
            $filePath = storage_path('app/public/' . $zipFiles[$i]['path']);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    }


    public function extractZipFile($zipFile)
    {

        $zip = new \ZipArchive();
        if ($zip->open(storage_path('app/public/' . $zipFile)) === TRUE) {

            // XML-Dateien extrahieren
            // Über alle Dateien im Zip iterieren
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                $fileInfo = pathinfo($filename);

                // Prüfen, ob es sich um eine XML-Datei handelt
                if (isset($fileInfo['extension']) && strtolower($fileInfo['extension']) === 'xml') {
                    // Einzigartigen Dateinamen generieren
                    $newFilename = 'imports/' . pathinfo($zipFile, PATHINFO_FILENAME) . '_' . uniqid() . '.xml';
                    $targetPath = storage_path('app/public/' . $newFilename);

                    // XML-Datei extrahieren und direkt in die Zieldatei schreiben
                    $fileContent = $zip->getFromIndex($i);
                    file_put_contents($targetPath, $fileContent);

                    $zip->close();
                    return $newFilename;
                }
            }
        }
    }


    function getSortedFilesWithFileFacade($directoryPath)
    {
        $path = storage_path('app/public/' . $directoryPath);

        if (!File::isDirectory($path)) {
            return [];
        }

        $files = File::files($path);

        $filesWithData = [];
        foreach ($files as $file) {
            $relativePath = str_replace(storage_path('app/public/'), '', $file->getPathname());
            $filesWithData[] = [
                'path'          => $relativePath,
                'filename'      => $file->getFilename(),
                'modified'      => $file->getMTime(),
                'modified_date' => date('Y-m-d H:i:s', $file->getMTime()),
                'size'          => $file->getSize()
            ];
        }

        // Sort by last modified timestamp
        usort($filesWithData, function ($a, $b) {
            return $a['modified'] <=> $b['modified'];
        });

        return $filesWithData;
    }

    public function extractBatchFiles($file)
    {
        $reader = new \XMLReader();
        $reader->open(storage_path('app/public/' . $file));

        while ($reader->read()) {
            if ($reader->nodeType === \XMLReader::ELEMENT && $reader->name === 'immobilie') {
                // Eindeutigen Dateinamen generieren
                $filename = 'batches/immobilie_' . uniqid() . '.xml';

                // Temporäre Datei erzeugen
                $tempFile = tempnam(sys_get_temp_dir(), 'immobilie_xml_');
                $writer = new \XMLWriter();
                $writer->openURI($tempFile);
                $writer->startDocument('1.0', 'UTF-8');
                $writer->setIndent(true);

                // Aktuellen "immobilie"-Node lesen und in die XML-Datei schreiben
                $immobilieXml = $reader->readOuterXml();
                $writer->writeRaw($immobilieXml);

                // XML-Writer schließen
                $writer->endDocument();
                $writer->flush();

                // Temporäre Datei in den öffentlichen Storage übertragen
                Storage::disk('public')->put(
                    $filename,
                    file_get_contents($tempFile)
                );

                // Temporäre Datei löschen
                unlink($tempFile);
            }
        }

    }
}