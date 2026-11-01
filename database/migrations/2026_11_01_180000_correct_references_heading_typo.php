<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Audit R34: the references heading on the Hausverwaltung start page was
 * stored as "Unsere Refrenzen - …" in the admin settings (the code default is
 * spelled correctly). Only the misspelled word is replaced, so every other
 * edit made in the admin stays untouched and a fresh database is not changed.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('settings')
            ->where('payload', 'like', '%Refrenz%')
            ->get(['id', 'payload'])
            ->each(fn (object $row) => DB::table('settings')->where('id', $row->id)->update([
                'payload' => str_replace('Refrenz', 'Referenz', $row->payload),
            ]));
    }

    public function down(): void
    {
        // Text correction, nothing to restore.
    }
};
