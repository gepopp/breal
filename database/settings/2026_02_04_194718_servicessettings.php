<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
public function up(): void
{
        $this->migrator->add('translatable_services.services_header_de', 'secure');
        $this->migrator->add('translatable_services.services_header_en', 'secure');
        $this->migrator->add('translatable_services.services_subheader_de', 'Unser Versprechen');
        $this->migrator->add('translatable_services.services_subheader_en', 'Our Promise');
        $this->migrator->add('translatable_services.services_introtext_de', '<p>Oft muss es schnell und einfach gehen - hier finden Sie alle wichtigen Unterlagen sowie nützliche Tipps für einen reibungslosen und effizienten Betrieb Ihres Gebäudes.</p>');
        $this->migrator->add('translatable_services.services_introtext_en', '<p>Often things need to be quick and easy - here you will find all important documents as well as useful tips for smooth and efficient operation of your building.</p>');
    }
};
