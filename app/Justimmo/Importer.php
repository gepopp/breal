<?php

namespace App\Justimmo;

use App\Jobs\ImportRealtyJob;
use App\Models\Realty;
use App\Models\User;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Importer
{
    protected array $filesToDelete = [];

    public static function import()
    {
        $instance = new self();



        if (!file_exists($fullZipPath)) {

            return;
        }

        Log::info('Found openimmo.zip file');

        // Extract and process
        Log::info('Extracting zip file: ' . $zipFilePath);
        $path = $instance->extractZipFile($zipFilePath);
        Log::info('Extracted XML file: ' . $path);

        // Mark processed zip file for deletion
        $instance->filesToDelete[] = $fullZipPath;

        Log::info('Extracting individual property XML files from: ' . $path);
        $instance->extractBatchFiles($path);

        // Mark extracted XML for deletion
        $instance->filesToDelete[] = storage_path('app/public/' . $path);

        $files = $instance->getSortedFilesWithFileFacade('batches');
        Log::info('Found ' . count($files) . ' property files to import');

        // Truncate realty table before processing new data
        Log::info('Truncating realty table');
        DB::table('realties')->truncate();

        // Dispatch individual jobs
        Log::info('Dispatching ' . count($files) . ' import jobs');
        foreach ($files as $file) {
            ImportRealtyJob::dispatch($file['path']);
        }

        Log::info('All import jobs dispatched successfully');

        // Delete all marked files
        Log::info('Cleaning up ' . count($instance->filesToDelete) . ' temporary files');
        $instance->deleteMarkedFiles();

        Log::info('=== Justimmo Import Completed ===');

        // Notify all admins
        foreach ($adminUsers as $admin) {
            Notification::make()
                ->title('Import gestartet! Es werden ' . count($files) . ' Objekte aktualisiert!')
                ->sendToDatabase($admin);
        }
    }

    /**
     * Delete all marked files
     */
    public function deleteMarkedFiles(): void
    {
        foreach ($this->filesToDelete as $file) {
            if (file_exists($file)) {
                unlink($file);
                Log::debug('Deleted file: ' . $file);
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


    public function notifyAdmins(string $message)
    {
        $adminUsers = User::where('admin', true)->get();
        foreach ($adminUsers as $admin) {
            Notification::make()
                ->title($message)
                ->sendToDatabase($admin);
        }
    }

    public function getZipFile()
    {

    }

}