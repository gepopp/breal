<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
public function up(): void
{
        $this->migrator->add('translatable_search.search_header_de', 'home');
        $this->migrator->add('translatable_search.search_header_en', 'home');
        $this->migrator->add('translatable_search.search_subheader_de', 'Immobiliensuche der be real makler');
        $this->migrator->add('translatable_search.search_subheader_en', 'Property Search by be real makler');
        $this->migrator->add('translatable_search.search_introtext_de', '<p>Hier finden Sie eine Auswahl der aktuellen, von uns angebotenen Immobilien. Wenn Sie am Verkauf oder der Vermietung Ihrer Liegenschaft interessiert sind, kontaktieren Sie uns gerne!&nbsp;</p>');
        $this->migrator->add('translatable_search.search_introtext_en', '<p>Here you will find a selection of the properties we currently offer. If you are interested in selling or renting your property, please feel free to contact us!&nbsp;</p>');
    }
};
