<?php

use Illuminate\Support\Facades\DB;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

/**
 * The public pages now read the translatable settings (German and English).
 * Their German texts were older copies, so the German text that is live today
 * (stored in the German-only "pages" settings) is copied over first, to keep
 * the German site unchanged. English texts are left as they are.
 */
return new class extends SettingsMigration
{
    private const TEXTS = [
        'services_header' => 'translatable_services',
        'services_subheader' => 'translatable_services',
        'services_introtext' => 'translatable_services',
        'contactform_heading' => 'translatable_contact',
        'contactpersons_heading' => 'translatable_contact',
        'contactpersons_introtext' => 'translatable_contact',
        'dpgr_text' => 'translatable_legal',
        'accessability_text' => 'translatable_legal',
    ];

    /**
     * One-word placeholders that the old "pages" migrations stored before the
     * texts were entered; they must not replace the real German defaults.
     */
    private const PLACEHOLDERS = ['<p>Datenschutz</p>', '<p>Barrierefreiheit</p>'];

    public function up(): void
    {
        foreach (self::TEXTS as $name => $group) {
            $live = DB::table('settings')->where('group', 'pages')->where('name', $name)->value('payload');
            $target = "{$group}.{$name}_de";

            if ($live === null || in_array(json_decode($live, true), self::PLACEHOLDERS, true) || ! $this->migrator->exists($target)) {
                continue;
            }

            $this->migrator->update($target, fn () => json_decode($live, true));
        }
    }
};
