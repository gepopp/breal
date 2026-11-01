<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Audit R25: the hero slideshows and intro photos shared one generic alt text
 * per section ("be real Intro Bild", "be real Makler: Immobilien in Wien",
 * "be real Team"). Each photo now carries its own description in the media
 * custom properties (`alt_de` / `alt_en`), which `<x-media-image :describe>`
 * prefers. Only photos of the page settings in the listed collections are
 * touched, and a description that already exists is kept.
 */
return new class extends Migration
{
    private const COLLECTIONS = ['hero_image', 'hero_images', 'text_image'];

    /**
     * File name prefix => [German, English] description.
     *
     * @var array<string, array{0: string, 1: string}>
     */
    private const DESCRIPTIONS = [
        'luca-dugaro-' => ['Prunkvolle Altbaufassaden in der Wiener Innenstadt', 'Ornate period building facades in Vienna\'s city centre'],
        'ali-nuredini-' => ['Enge Wiener Gasse mit hellen Altbauten', 'Narrow Viennese street lined with light-coloured period buildings'],
        'dan-v-' => ['Belebte Straßenkreuzung mit historischen Gebäuden', 'Busy street crossing with historic buildings'],
        'anders-holm-jensen-' => ['Moderner Wohnbau aus Ziegel mit Balkonen vor blauem Himmel', 'Modern brick apartment building with balconies against a blue sky'],
        'nate-watson-' => ['Moderne Wohnhausfassade unter Wolkenhimmel', 'Modern apartment building facade under a cloudy sky'],
        'sams-solutions-' => ['Bauarbeiter mit Helm und Warnweste auf einer Baustelle', 'Construction worker in a helmet and high-visibility vest on a building site'],
        'morgan-von-gunten-' => ['Vermesser mit Schutzhelm am Nivelliergerät', 'Surveyor in a hard hat at a levelling instrument'],
        'annie-gray-' => ['Handwerker beim Zuschneiden einer Leiste bei Umbauarbeiten', 'Craftsman cutting a trim board during renovation work'],
        'bill-mead-' => ['Monteur trägt ein Solarpaneel auf ein Dach', 'Installer lifting a solar panel onto a roof'],
        '250226-Lukas-Eybel_Tanja' => ['Tanja Bachschwöller-Eybel und Lukas Eybel im Besprechungsraum von be real', 'Tanja Bachschwöller-Eybel and Lukas Eybel in the be real meeting room'],
        '250226-Lukas-Eybel_F.Jana' => ['Lukas Eybel lehnt lächelnd an einer Empfangstheke im Büro', 'Lukas Eybel smiling and leaning on a reception desk in the office'],
        '250226-Lukas-Eybel_F.jpg' => ['Lukas Eybel im Büro von be real, im Hintergrund das Firmenlogo', 'Lukas Eybel in the be real office with the company logo in the background'],
    ];

    public function up(): void
    {
        DB::table('media')
            ->where('model_type', 'like', '%Settings%')
            ->whereIn('collection_name', self::COLLECTIONS)
            ->get(['id', 'file_name', 'custom_properties'])
            ->each(function (object $row): void {
                foreach (self::DESCRIPTIONS as $prefix => [$german, $english]) {
                    if (! str_starts_with($row->file_name, $prefix)) {
                        continue;
                    }

                    $properties = json_decode((string) $row->custom_properties, true) ?: [];

                    $properties['alt_de'] ??= $german;
                    $properties['alt_en'] ??= $english;

                    DB::table('media')->where('id', $row->id)->update([
                        'custom_properties' => json_encode($properties, JSON_UNESCAPED_UNICODE),
                    ]);

                    return;
                }
            });
    }

    public function down(): void
    {
        // Descriptions only, nothing to restore.
    }
};
