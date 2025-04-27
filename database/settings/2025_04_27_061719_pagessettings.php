<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
public function up(): void
{
        $this->migrator->add('pages.services_header', 'secure');
        $this->migrator->add('pages.services_subheader', 'Unser Versprechen');
        $this->migrator->add('pages.services_introtext', '<p>Oft muss es schnell und einfach gehen - hier finden Sie alle wichtigen Unterlagen sowie nützliche Tipps für einen reibungslosen und effizienten Betrieb Ihres Gebäudes.</p>');
    }
};
