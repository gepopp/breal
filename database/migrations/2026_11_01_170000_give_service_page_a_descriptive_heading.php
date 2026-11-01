<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * The service page was headed "Unser Versprechen" / "Our Promise", which says
 * nothing about the page (forms and documents for owners and residents).
 * Only the exact old headings are replaced; a heading edited in the admin stays.
 */
return new class extends Migration
{
    /**
     * Setting name => [old heading, new heading].
     *
     * @var array<string, array{0: string, 1: string}>
     */
    private const HEADINGS = [
        'services_subheader' => ['Unser Versprechen', 'Service und Formulare für Eigentümer:innen und Bewohner:innen'],
        'services_subheader_de' => ['Unser Versprechen', 'Service und Formulare für Eigentümer:innen und Bewohner:innen'],
        'services_subheader_en' => ['Our Promise', 'Service and forms for owners and residents'],
    ];

    public function up(): void
    {
        foreach (self::HEADINGS as $name => [$old, $new]) {
            DB::table('settings')
                ->whereIn('group', ['pages', 'translatable_services'])
                ->where('name', $name)
                ->where('payload', json_encode($old, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
                ->update(['payload' => json_encode($new, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]);
        }
    }

    public function down(): void
    {
        // Text correction, nothing to restore.
    }
};
