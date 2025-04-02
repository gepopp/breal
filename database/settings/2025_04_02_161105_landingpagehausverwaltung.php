<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
public function up(): void
{
        $this->migrator->add('hausverwaltung.hero_image', 0);
        $this->migrator->add('hausverwaltung.hero_header', 'welcome');
        $this->migrator->add('hausverwaltung.hero_subheader', 'Hausverwaltung und Immobilienmanagement');
    }
};
