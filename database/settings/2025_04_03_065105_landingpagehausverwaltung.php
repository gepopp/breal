<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
public function up(): void
{
        $this->migrator->add('hausverwaltung.text_image', 0);
        $this->migrator->add('hausverwaltung.text_image_alt', 'Bontus Eybel Intro Bild');
    }
};
