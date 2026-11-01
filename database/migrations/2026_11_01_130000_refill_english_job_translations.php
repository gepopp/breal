<?php

use Illuminate\Database\Migrations\Migration;

/**
 * The first run of fill_missing_english_translations skipped the WEG job ad:
 * its "English" description was an older German version of the text, neither
 * empty nor identical to the current German one. The translation migration
 * recognises that text now, running it again is harmless.
 */
return new class extends Migration
{
    public function up(): void
    {
        (require __DIR__.'/2026_10_31_240000_fill_missing_english_translations.php')->up();
    }

    public function down(): void
    {
        // Added translations are content, nothing to restore.
    }
};
