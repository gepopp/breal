<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Audit A10: the contact intro text linked the damage report form with the
 * absolute production address, so on staging the link left the staging site.
 * A relative link works on every host. Only the exact old address is replaced.
 */
return new class extends Migration
{
    private const ABSOLUTE = '#https://(?:www\.)?bereal-immobilien\.at/service\?tab=schadensformular#';

    public function up(): void
    {
        DB::table('settings')
            ->where('name', 'like', 'contact_introtext%')
            ->where('payload', 'like', '%schadensformular%')
            ->get()
            ->each(function (object $row): void {
                $text = json_decode($row->payload);

                if (! is_string($text)) {
                    return;
                }

                $path = str_ends_with($row->name, '_en') ? '/en/service?tab=schadensformular' : '/service?tab=schadensformular';

                DB::table('settings')->where('id', $row->id)->update([
                    'payload' => json_encode(preg_replace(self::ABSOLUTE, $path, $text), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                ]);
            });
    }

    public function down(): void
    {
        // Link correction, nothing to restore.
    }
};
