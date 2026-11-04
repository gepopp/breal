<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * The reference photos only had the reference title as alt text. Each photo now
 * carries its own description in the media custom properties (`alt_de` / `alt_en`),
 * prefixed with the reference name (the address), which `<x-media-image :describe>`
 * prefers. A description that already exists is kept.
 */
return new class extends Migration
{
    /**
     * Reference slug => [German, English] description.
     *
     * @var array<string, array{0: string, 1: string}>
     */
    private const DESCRIPTIONS = [
        'franziskaner-platz' => ['Franziskaner Platz - Rosafarbenes Zinshaus mit blauen Fensterrahmen und Balkon über dem Torbogen', 'Franziskaner Platz - Pink apartment building with blue window frames and a balcony above the arched entrance'],
        'dr-karl-lueger-platz' => ['Dr. Karl Lueger Platz - Weißes Eckhaus mit Geschäften im Erdgeschoss unter blauem Himmel', 'Dr. Karl Lueger Platz - White corner building with shops on the ground floor under a blue sky'],
        'erika-krenn-promenade' => ['Erika-Krenn-Promenade - Historische Ziegelhalle mit Rundbögen und Gastgarten, dahinter ein moderner Wohnbau', 'Erika-Krenn-Promenade - Historic brick hall with round arches and an outdoor terrace, with a modern apartment block behind it'],
        'franz-josefs-kai' => ['Franz-Josefs-Kai - Sonnenbeschienener Altbau mit Stuckfassade und Straßenbäumen', 'Franz-Josefs-Kai - Sunlit period building with a stucco facade and street trees'],
        'triester-strasse' => ['Triester Straße - Flaches Gewerbegebäude mit Glasfront, Fahrradgeschäft und Parkplatz', 'Triester Straße - Single-storey commercial building with a glass front, a bicycle store and a car park'],
        'rotenturmstrasse' => ['Rotenturmstraße - Prunkvolle Gründerzeitfassade mit Geschäften im Erdgeschoss', 'Rotenturmstraße - Ornate period facade with shops on the ground floor'],
        'mseumsquartier' => ['Museumsquartier - Gelbe Barockfassade des MuseumsQuartiers mit Fußgängern am Zebrastreifen', 'Museumsquartier - Yellow baroque facade of the MuseumsQuartier with pedestrians on a zebra crossing'],
        'jorgerstrasse' => ['Jörgerstraße - Reich verziertes Gründerzeit-Eckhaus mit Erker und großem Laubbaum', 'Jörgerstraße - Richly ornamented period corner building with an oriel window and a large tree'],
        'grosse-sperlgasse' => ['Große Sperlgasse - Weiß renoviertes Eckhaus mit Stuckverzierungen und Café-Terrasse', 'Große Sperlgasse - White renovated corner building with stucco decoration and a café terrace'],
    ];

    public function up(): void
    {
        foreach (self::DESCRIPTIONS as $slug => [$german, $english]) {
            $reference = DB::table('references')->where('slug', $slug)->first(['id']);

            if ($reference === null) {
                continue;
            }

            DB::table('media')
                ->where('model_type', 'like', '%Reference')
                ->where('model_id', $reference->id)
                ->where('collection_name', 'titleimage')
                ->get(['id', 'custom_properties'])
                ->each(function (object $row) use ($german, $english): void {
                    $properties = json_decode((string) $row->custom_properties, true) ?: [];

                    $properties['alt_de'] ??= $german;
                    $properties['alt_en'] ??= $english;

                    DB::table('media')->where('id', $row->id)->update([
                        'custom_properties' => json_encode($properties, JSON_UNESCAPED_UNICODE),
                    ]);
                });
        }
    }

    public function down(): void
    {
        // Text enrichment, nothing to restore.
    }
};
