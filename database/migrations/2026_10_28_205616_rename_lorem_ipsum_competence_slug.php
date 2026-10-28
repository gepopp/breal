<?php

use App\Models\Competence;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    private const OLD_SLUG = 'alias-qui-vel-ea-repellendus';

    private const NEW_SLUG = 'bauverwaltung';

    /**
     * The competence "Bauverwaltung" kept the placeholder slug of the seed
     * data. The old address is redirected in config/redirects.php.
     */
    public function up(): void
    {
        if (Competence::withoutGlobalScopes()->where('slug', self::NEW_SLUG)->exists()) {
            return;
        }

        Competence::withoutGlobalScopes()->where('slug', self::OLD_SLUG)->update(['slug' => self::NEW_SLUG]);
    }

    public function down(): void
    {
        Competence::withoutGlobalScopes()->where('slug', self::NEW_SLUG)->update(['slug' => self::OLD_SLUG]);
    }
};
