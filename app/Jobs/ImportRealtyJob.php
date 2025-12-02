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
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $json = Storage::disk('public')->get($this->path);
        $array = json_decode($json, true);

        $openimmo_obid = $array['verwaltung_techn']['openimmo_obid'];

        $nutzungsart = '';
        foreach ($array['objektkategorie']['nutzungsart']['@attributes'] as $key => $value) {
            if ($value == '1') {
                $nutzungsart = $key;
            }
        }

        $preis = null;
        if (isset($array['preise']['kaufpreisbrutto'])) {
            $preis = $array['preise']['kaufpreisbrutto'];
        } elseif (isset($array['preise']['gesamtmietebrutto'])) {
            $preis = $array['preise']['gesamtmietebrutto'];
        }

        $vermarktungsart = isset($array['objektkategorie']['vermarktungsart']['@attributes']['KAUF']) &&
        $array['objektkategorie']['vermarktungsart']['@attributes']['KAUF'] == '1'
            ? 'kauf' : 'miete';


        Realty::updateOrCreate(
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
                'preis'           => $preis,
                'vermarktungsart' => $vermarktungsart,
                'nutzungsart'     => $nutzungsart ?? null,
                'plz'             => $array['geo']['plz'] ?? null,
                'ort'             => $array['geo']['ort'] ?? null,
                'strasse'         => $array['geo']['strasse'] ?? null,
                'hausnummer'      => $array['geo']['hausnummer'] ?? null,
                'bundesland'      => $array['geo']['bundesland'] ?? null,
                'wohnungsnummer'  => $array['geo']['wohnungsnr'] ?? null,
                'lat'             => $array['geo']['user_defined_simplefield'][0] ?? null,
                'lng'             => $array['geo']['user_defined_simplefield'][1] ?? null,
                'data'            => $array,
            ]
        );
    }
}
