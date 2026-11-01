<?php

use Illuminate\Database\Migrations\Migration;

/**
 * The first run of fill_missing_english_translations skipped the WEG job ad:
 * its English text was a copy of the German one that differed in whitespace only.
 * The comparison ignores whitespace now, running it again is harmless.
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
