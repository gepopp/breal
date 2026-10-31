<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Audit A11: the importer only read "gesamtflaeche" into the living area
 * column, so the search results said "Auf Anfrage" for properties whose feed
 * data does contain a "wohnflaeche". Fills the empty column from that data.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('realties')
            ->whereNull('wohnflaeche')
            ->get(['id', 'data'])
            ->each(function (object $realty): void {
                $areas = json_decode((string) $realty->data, true)['flaechen'] ?? [];
                $area = $areas['wohnflaeche'] ?? $areas['gesamtflaeche'] ?? null;

                if (is_numeric($area)) {
                    DB::table('realties')->where('id', $realty->id)->update(['wohnflaeche' => (float) $area]);
                }
            });
    }

    public function down(): void
    {
        // Filled from the stored feed data, nothing to restore.
    }
};
