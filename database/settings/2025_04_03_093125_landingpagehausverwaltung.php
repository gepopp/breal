<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
public function up(): void
{
        $this->migrator->add('hausverwaltung.timeline_header', 'now');
        $this->migrator->add('hausverwaltung.timeline_subheader', 'Die Geschichte von be real');
        $this->migrator->add('hausverwaltung.timeline_intro', '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>');
    }
};
