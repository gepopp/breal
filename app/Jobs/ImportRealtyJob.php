<?php

namespace App\Jobs;

use App\Models\Realty;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

class ImportRealtyJob implements ShouldQueue
{
    use Queueable, Batchable;


    /**
     * Create a new job instance.
     */
    public function __construct(public string $path)
    {

        $xml = simplexml_load_file(storage_path('app/public/' . $path));;
        $json = json_encode($xml);
        $array = json_decode($json, true);


        $nutzungsart = '';
        foreach ($array['objektkategorie']['nutzungsart']['@attributes'] as $key => $value) {
            if ($value == '1') {
                $nutzungsart = $key;
            }
        }


        Realty::updateOrCreate(
            [
                'openimmo_obid' => $array['verwaltung_techn']['openimmo_obid'],
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
                'path'            => 'realties/' . $array['verwaltung_techn']['openimmo_obid'] . '.json'
            ]);


        Storage::disk('public')->put('realties/' . $array['verwaltung_techn']['openimmo_obid'] . '.json', $json);
        unlink(storage_path('app/public/' . $path));
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }
}
