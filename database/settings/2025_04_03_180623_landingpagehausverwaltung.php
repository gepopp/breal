<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
public function up(): void
{
        $this->migrator->add('hausverwaltung.service_heading', 'trusted');
        $this->migrator->add('hausverwaltung.service_subheading', 'Unser Service');
        $this->migrator->add('hausverwaltung.service_introtext', '<p>Partnerschaftliche Zusammenarbeit steht für uns an erster Stelle! Wir wissen, dass Vertrauen und gegenseitiges Verständnis die Basis ist, um Ihre Immobilien bestmöglich zu verwalten. Umfangreiche Beratung, persönliche Betreuung und individuelle Lösungen sind die Grundpfeiler, damit Sie sich rundum gut betreut fühlen.</p>');
    }
};
