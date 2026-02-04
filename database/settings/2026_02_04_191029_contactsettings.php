<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
public function up(): void
{
        $this->migrator->add('translatable_contact.contact_header_de', 'together');
        $this->migrator->add('translatable_contact.contact_header_en', 'together');
        $this->migrator->add('translatable_contact.contact_subheader_de', 'Ihr Kontakt zu be real immobilien');
        $this->migrator->add('translatable_contact.contact_subheader_en', 'Your Contact to be real immobilien');
        $this->migrator->add('translatable_contact.contact_introtext_de', '<p>Am liebsten sprechen wir persönlich mit Ihnen über Ihre Anliegen. Wir freuen uns, von Ihnen zu hören und gemeinsam Großartiges zu schaffen.</p><p><strong>Für die Meldungen von etwaigen Schäden in Liegenschaften verwenden Sie bitte das </strong><a href="https://bereal-immobilien.at/service?tab=schadensformular"><strong>Schadensformular</strong></a><strong>!</strong></p>');
        $this->migrator->add('translatable_contact.contact_introtext_en', '<p>We prefer to speak with you personally about your concerns. We look forward to hearing from you and creating something great together.</p><p><strong>To report any damage to properties, please use the </strong><a href="https://bereal-immobilien.at/service?tab=schadensformular"><strong>damage report form</strong></a><strong>!</strong></p>');
        $this->migrator->add('translatable_contact.contactform_heading_de', 'Sagen Sie uns Hallo!');
        $this->migrator->add('translatable_contact.contactform_heading_en', 'Say Hello to Us!');
        $this->migrator->add('translatable_contact.contactform_email', 'office@bereal-immobilien.at');
        $this->migrator->add('translatable_contact.contactform_phone', '+43 1 535 36 19');
        $this->migrator->add('translatable_contact.contactform_address', 'Franz-Josefs-Kai 65, 1010 Wien');
        $this->migrator->add('translatable_contact.makler_contactform_email', 'office@bereal-makler.at');
        $this->migrator->add('translatable_contact.makler_contactform_phone', '+43 1 535 36 19');
        $this->migrator->add('translatable_contact.makler_contactform_address', 'Franz-Josefs-Kai 65, 1010 Wien');
        $this->migrator->add('translatable_contact.technik_contactform_email', 'office@bereal-technik.at');
        $this->migrator->add('translatable_contact.technik_contactform_phone', '+43 1 535 36 19');
        $this->migrator->add('translatable_contact.technik_contactform_address', 'Franz-Josefs-Kai 65, 1010 Wien');
        $this->migrator->add('translatable_contact.contactform_terms_de', 'Ich bin mit der Verarbeitung und Speicherung meiner Daten, sowie mit der Kontaktaufnahme via E-Mail oder Telefon im Zuge der Bearbeitung meiner Anfrage einverstanden.');
        $this->migrator->add('translatable_contact.contactform_terms_en', 'I agree to the processing and storage of my data, as well as to being contacted via email or telephone in the course of processing my inquiry.');
        $this->migrator->add('translatable_contact.contactpersons_heading_de', 'Ihre Ansprechpartner!');
        $this->migrator->add('translatable_contact.contactpersons_heading_en', 'Your Contact Persons!');
        $this->migrator->add('translatable_contact.contactpersons_introtext_de', 'Zusammenarbeit funktioniert am besten, wenn sie wissen, wer am anderen Ende der Leitung sitzt. Hier dürfen wir Ihnen die zuständigen MitarbeiterInnen vorstellen.');
        $this->migrator->add('translatable_contact.contactpersons_introtext_en', 'Collaboration works best when you know who is on the other end of the line. Here we would like to introduce you to the responsible employees.');
    }
};
