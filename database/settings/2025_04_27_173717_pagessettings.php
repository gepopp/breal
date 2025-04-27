<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
public function up(): void
{
        $this->migrator->add('pages.faq_header', 'informed');
        $this->migrator->add('pages.faq_subheader', 'Antworten auf häufig gestellte Fragen');
        $this->migrator->add('pages.faq_introtext', '<p>Hausverwaltung ist ein komplexes Thema. Da kann es schon mal zu Fragen kommen. Wir beantworten Ihre Fragen rund um das Thema Immobilienmanagement und Hausverwaltung.</p>');
    }
};
