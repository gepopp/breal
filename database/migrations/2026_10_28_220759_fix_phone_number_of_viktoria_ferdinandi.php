<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * The extension was entered without the Vienna area code "1" (every other
 * number of the company is +43 1 535 36 19 ...). Only that exact wrong value
 * is changed.
 */
return new class extends Migration
{
    private const WRONG = '+43 535 36 19 887';

    private const RIGHT = '+43 1 535 36 19 887';

    public function up(): void
    {
        DB::table('contactpeople')
            ->where('email', 'v.ferdinandi@bereal-immobilien.at')
            ->where('phone', self::WRONG)
            ->update(['phone' => self::RIGHT]);
    }

    public function down(): void
    {
        DB::table('contactpeople')
            ->where('email', 'v.ferdinandi@bereal-immobilien.at')
            ->where('phone', self::RIGHT)
            ->update(['phone' => self::WRONG]);
    }
};
