<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
public function up(): void
{
        $this->migrator->add('pages.hausverwaltung_leistungen_header', 'competent');
        $this->migrator->add('pages.hausverwaltung_leistungen_subheader', 'Unsere Leistungen');
        $this->migrator->add('pages.hausverwaltung_leistungen_introtext', '<p>Wir brennen für Hausverwaltung - von Wohneigentumsverwaltung und Mietrechtsgesetz bis zu Bauverwaltung und allgemeiner Beratung – wir kümmern uns darum, dass Sie sich rundum gut betreut fühlen. Dabei stehen partnerschaftliche Zusammenarbeit, individuelle Lösungen und umfangreiche Beratung an erster Stelle.</p>');
        $this->migrator->add('pages.immobilien_leistungen_header', 'competent');
        $this->migrator->add('pages.immobilien_leistungen_subheader', 'Unsere Leistungen');
        $this->migrator->add('pages.immobilien_leistungen_introtext', '<p>Wir brennen für Hausverwaltung - von Wohneigentumsverwaltung und Mietrechtsgesetz bis zu Bauverwaltung und allgemeiner Beratung – wir kümmern uns darum, dass Sie sich rundum gut betreut fühlen. Dabei stehen partnerschaftliche Zusammenarbeit, individuelle Lösungen und umfangreiche Beratung an erster Stelle.</p>');
        $this->migrator->add('pages.technik_leistungen_header', 'competent');
        $this->migrator->add('pages.technik_leistungen_subheader', 'Unsere Leistungen');
        $this->migrator->add('pages.technik_leistungen_introtext', '<p>Wir brennen für Hausverwaltung - von Wohneigentumsverwaltung und Mietrechtsgesetz bis zu Bauverwaltung und allgemeiner Beratung – wir kümmern uns darum, dass Sie sich rundum gut betreut fühlen. Dabei stehen partnerschaftliche Zusammenarbeit, individuelle Lösungen und umfangreiche Beratung an erster Stelle.</p>');
    }
};
