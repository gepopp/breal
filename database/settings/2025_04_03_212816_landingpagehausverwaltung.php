<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
public function up(): void
{
        $this->migrator->add('hausverwaltung.reference_header', 'on');
        $this->migrator->add('hausverwaltung.reference_subheader', 'Aktuelle Kundenstimmen und Referenzen');
        $this->migrator->add('hausverwaltung.reference_introtext', '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>');
    }
};
