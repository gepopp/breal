<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * "Unsere Geschichte": "Seit mehreren Jahrzehnten" / "For several decades" becomes
 * "Seit fast 30 Jahren" / "For almost 30 years" (client wording). Only the phrase is
 * replaced, so other edits made in the admin stay untouched.
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->replacePhrase('timeline_intro_de', 'Seit mehreren Jahrzehnten', 'Seit fast 30 Jahren');
        $this->replacePhrase('timeline_intro_en', 'For several decades', 'For almost 30 years');
    }

    public function down(): void
    {
        // Text correction, nothing to restore.
    }

    private function replacePhrase(string $name, string $old, string $new): void
    {
        DB::table('settings')
            ->where('group', 'like', '%hausverwaltung%')
            ->where('name', $name)
            ->where('payload', 'like', '%'.$old.'%')
            ->get()
            ->each(fn (object $row) => DB::table('settings')->where('id', $row->id)->update([
                'payload' => str_replace($old, $new, $row->payload),
            ]));
    }
};
