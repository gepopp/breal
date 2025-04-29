<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
public function up(): void
{
        $this->migrator->add('MaklerSettings.intro_title', 'realtor');
        $this->migrator->add('MaklerSettings.intro_subtitle', 'Keine Immobilie gleicht der anderen und jede hat ihre eigene Geschichte.');
        $this->migrator->add('MaklerSettings.intro_description', '<p>Bei uns dreht sich alles um die Immobilienvermittlung im Bereich Wohnen und Gewerbe, sei es in Miete oder Eigentum.</p><p>Gerne stehen wir Ihnen neben der Immobilienvermittlung einzelner Objekte auch für Vermarktungsfragen oder mit einer auf Sie und Ihr Projekt angepasste Vermarktungsstrategie gerne zur Verfügung.</p>');
    }
};
