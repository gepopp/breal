<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
public function up(): void
{
        $this->migrator->add('pages.team_header', 'team');
        $this->migrator->add('pages.team_subheader', 'Das Team von Bontus Eybel');
        $this->migrator->add('pages.team_introtext', '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>');
    }
};
