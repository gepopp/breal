<?php

namespace App\Jobs;

use App\Models\Realty;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImportRealtyJob implements ShouldQueue
{
    use Queueable;


    /**
     * Create a new job instance.
     */
    public function __construct(public string $path)
    {
        dd($path);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
            $json = Storage::disk('public')->get($this->path);
            $array = json_decode($json, true);

            dd($array);

            $openimmo_obid = $array['verwaltung_techn']['openimmo_obid'] ?? null;

            if (!$openimmo_obid) {
                Log::warning('Missing openimmo_obid in file: ' . $this->path);
                unlink($filePath);
                return;
            }

            $nutzungsart = '';
            foreach ($array['objektkategorie']['nutzungsart']['@attributes'] as $key => $value) {
                if ($value == '1') {
                    $nutzungsart = $key;
                }
            }

            $realty = Realty::updateOrCreate(
                [
                    'openimmo_obid' => $openimmo_obid,
                ],
                [
                    'objektnr_intern' => $array['verwaltung_techn']['objektnr_intern'] ?? null,
                    'objektnr_extern' => $array['verwaltung_techn']['objektnr_extern'] ?? null,
                    'title'           => $array['freitexte']['objekttitel'] ?? null,
                    'beschreibung'    => $array['freitexte']['objektbeschreibung'] ?? null,
                    'zimmer'          => $array['flaechen']['anzahl_zimmer'] ?? null,
                    'wohnflaeche'     => $array['flaechen']['gesamtflaeche'] ?? null,
                    'preis'           => ($array['preise']['kaufpreisbrutto'] ?? null) ?:
                        ($array['preise']['gesamtmietebrutto'] ?? null),
                    'vermarktungsart' => isset($array['objektkategorie']['vermarktungsart']['@attributes']['KAUF']) &&
                    $array['objektkategorie']['vermarktungsart']['@attributes']['KAUF'] == '1'
                        ? 'kauf' : 'miete',
                    'nutzungsart'     => $nutzungsart ?? null,
                    'plz'             => $array['geo']['plz'] ?? null,
                    'ort'             => $array['geo']['ort'] ?? null,
                    'strasse'         => $array['geo']['strasse'] ?? null,
                    'hausnummer'      => $array['geo']['hausnummer'] ?? null,
                    'bundesland'      => $array['geo']['bundesland'] ?? null,
                    'wohnungsnummer'  => $array['geo']['wohnungsnr'] ?? null,
                    'lat'             => $array['geo']['user_defined_simplefield'][0] ?? null,
                    'lng'             => $array['geo']['user_defined_simplefield'][1] ?? null,
                    'path'            => 'realties/' . $openimmo_obid . '.json'
                ]
            );

            Storage::disk('public')->put('realties/' . $openimmo_obid . '.json', $json);

            Log::info('Successfully imported realty: ' . $openimmo_obid . ' - ' . ($array['freitexte']['objekttitel'] ?? 'No title'));

            // Clean up batch file
            unlink($filePath);


    }
}
