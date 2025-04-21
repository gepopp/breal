<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
public function up(): void
{
        $this->migrator->add('pages.imprint_text', '<p>Impressum</p>');
        $this->migrator->add('pages.dpgr_text', '<p>Datenschutz</p>');
        $this->migrator->add('pages.accessability_text', '<p>Barrierefreiheit</p>');
    }
};
