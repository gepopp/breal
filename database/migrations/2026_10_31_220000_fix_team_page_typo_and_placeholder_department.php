<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Staging feedback: a typo in the team page intro text and a lorem ipsum
 * department ("soluta") that still showed up as a heading on /makler/team.
 * Both changes only apply to the exact old value, so edits made in the admin
 * in the meantime are never overwritten and a fresh database is untouched.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('settings')
            ->where('name', 'like', 'team_introtext%')
            ->where('payload', 'like', '%Zufriendheit%')
            ->get()
            ->each(fn (object $row) => DB::table('settings')->where('id', $row->id)->update([
                'payload' => str_replace('Zufriendheit', 'Zufriedenheit', $row->payload),
            ]));

        DB::table('departments')
            ->where('company', 'Makler')
            ->where('name', json_encode(['de' => 'soluta', 'en' => 'soluta']))
            ->whereNotExists(fn ($query) => $query->select(DB::raw(1))->from('contactpeople')->whereColumn('contactpeople.department_id', 'departments.id'))
            ->delete();
    }

    public function down(): void
    {
        // Text correction and removal of a placeholder, nothing to restore.
    }
};
