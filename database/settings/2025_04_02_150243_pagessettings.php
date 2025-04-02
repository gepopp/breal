<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
public function up(): void
{
        $this->migrator->add('pages.contact_header', 'together');
        $this->migrator->add('pages.contact_subheader', 'Ihr Kontakt zu Bontus Eybel');
        $this->migrator->add('pages.contactform_heading', 'Sagen Sie uns Hallo!');
        $this->migrator->add('pages.contactform_email', 'office@bontus-eybel.at');
        $this->migrator->add('pages.contactform_phone', '+43 1 535 36 19');
        $this->migrator->add('pages.contactform_address', 'Franz Josefs Kai 65, 1010 Wien');
        $this->migrator->add('pages.contactform_terms', 'Ich bin mit der Verarbeitung und Speicherung meiner Daten, sowie mit der Kontaktaufnahme via E-Mail oder Telefon im Zuge der Bearbeitung meiner Anfrage einverstanden.');
        $this->migrator->add('pages.contactpersons_heading', 'Ihre Ansprechpartner!');
    }
};
