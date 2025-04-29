<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
public function up(): void
{
        $this->migrator->add('MaklerSettings.about_heading', 'Geschäftsführerin und Eigentümerin Tanja Bachschwöller, BA');
    }
};
