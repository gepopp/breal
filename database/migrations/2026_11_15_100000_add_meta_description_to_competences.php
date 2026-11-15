<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Gives every service its own text for the search result (meta description), German and English. Until it is filled
 * in, the page keeps using the intro text. Bing reported seven Technik services whose intro text is only a short
 * motto (32 to 61 characters); the texts below are the starting values for them and can be changed in the admin.
 * Only empty values are filled in, so a text entered in the admin is never overwritten.
 */
return new class extends Migration
{
    /**
     * @var array<string, array<string, string>>
     */
    private const STARTING_VALUES = [
        'fordermanagement' => [
            'de' => 'Fördermanagement von be real Technik: Wir finden passende Förderungen für Ihre Sanierung und begleiten Sie von der Antragstellung bis zum Fördernachweis.',
            'en' => 'Funding management by be real Technik: we find suitable grants for your refurbishment and support you from the application to the proof of funding.',
        ],
        'objektbegehungen' => [
            'de' => 'Objektbegehungen von be real Technik: regelmäßige Kontrolle Ihrer Liegenschaft mit Protokoll und konkreten Handlungsempfehlungen für Wartung und Sicherheit.',
            'en' => 'Property inspections by be real Technik: regular checks of your building with reports and clear recommendations for maintenance and safety.',
        ],
        'sanierungskonzepte' => [
            'de' => 'Sanierungskonzepte von be real Technik: thermische Sanierung, Haustechnik und Heizungsumstellung, abgestimmt auf Zustand, Nutzung und Budget Ihres Gebäudes.',
            'en' => 'Refurbishment concepts by be real Technik: thermal retrofit, building services and heating conversion, tailored to condition, use and budget.',
        ],
        'energieausweis' => [
            'en' => 'Energy performance certificates by be real Technik: for new builds, existing buildings and refurbishments, with advice on improving energy efficiency.',
        ],
    ];

    public function up(): void
    {
        Schema::table('competences', function (Blueprint $table) {
            $table->text('meta_description')->nullable();
        });

        foreach (self::STARTING_VALUES as $slug => $texts) {
            $row = DB::table('competences')->where('slug', $slug)->first(['id', 'meta_description']);

            if ($row === null) {
                continue;
            }

            $stored = json_decode((string) $row->meta_description, true);
            $stored = is_array($stored) ? $stored : [];

            foreach ($texts as $locale => $text) {
                if (blank($stored[$locale] ?? null)) {
                    $stored[$locale] = $text;
                }
            }

            DB::table('competences')->where('id', $row->id)->update([
                'meta_description' => json_encode($stored, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('competences', function (Blueprint $table) {
            $table->dropColumn('meta_description');
        });
    }
};
