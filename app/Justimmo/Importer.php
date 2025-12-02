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

        $xmlFile = $instance->extractZipFile();

        if(!$xmlFile)
        {
            return;
        }

        $instance->extractBatchFiles($xmlFile);

    }




    public function extractZipFile(): string|bool
    {
        if (!Storage::disk('public')->exists('imports/openimmo.zip')) {
            Log::channel('importer')->info('No openimmo.zip file found');
            return false;
        }

        $zipPath = Storage::disk('public')->path('imports/openimmo.zip');

        $zip = new \ZipArchive();

        if ($zip->open($zipPath) !== true) {
            throw new ImportException('Could not open zip file: ' . $zipPath);
        }


        for ($i = 0; $i < $zip->numFiles; $i++) {

            $filename = $zip->getNameIndex($i);

            $fileInfo = pathinfo($filename);


            if (empty($fileInfo['extension'])) {
                continue;
            }

            if ($fileInfo['extension'] == 'xml') {
                $fileContent = $zip->getFromIndex($i);
                $xmlFile = 'imports/' . $fileInfo['basename'];

                $put = Storage::disk('public')->put($xmlFile, $fileContent);

                if ($put) {
                    return $xmlFile;
                }
            }
        }
        return false;
    }



    public function extractBatchFiles($file)
    {
        Storage::disk('public')->deleteDirectory('batches');
        Storage::disk('public')->makeDirectory('batches');


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
}

class ImportException extends \Exception{}