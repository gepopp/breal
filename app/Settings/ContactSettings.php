<?php

namespace App\Settings;

class ContactSettings extends BaseSettings
{
    // Contact Page
    public string $contact_header_de = 'together';

    public string $contact_header_en = 'together';

    public string $contact_subheader_de = 'Ihr Kontakt zu be real immobilien';

    public string $contact_subheader_en = 'Your Contact to be real immobilien';

    public string $contact_introtext_de = '<p>Am liebsten sprechen wir persönlich mit Ihnen über Ihre Anliegen. Wir freuen uns, von Ihnen zu hören und gemeinsam Großartiges zu schaffen.</p><p><strong>Für die Meldungen von etwaigen Schäden in Liegenschaften verwenden Sie bitte das </strong><a href="https://bereal-immobilien.at/service?tab=schadensformular"><strong>Schadensformular</strong></a><strong>!</strong></p>';

    public string $contact_introtext_en = '<p>We prefer to speak with you personally about your concerns. We look forward to hearing from you and creating something great together.</p><p><strong>To report any damage to properties, please use the </strong><a href="https://bereal-immobilien.at/service?tab=schadensformular"><strong>damage report form</strong></a><strong>!</strong></p>';

    // Contact Form Heading
    public string $contactform_heading_de = 'Sagen Sie uns Hallo!';

    public string $contactform_heading_en = 'Say Hello to Us!';

    // General Contact Information
    public string $contactform_email = 'office@bereal-immobilien.at';

    public string $contactform_phone = '+43 1 535 36 19';

    public string $contactform_address = 'Franz-Josefs-Kai 65, 1010 Wien';

    // Makler (Real Estate Agent) Contact Information
    public string $makler_contactform_email = 'office@bereal-makler.at';

    public string $makler_contactform_phone = '+43 1 535 36 19';

    public string $makler_contactform_address = 'Franz-Josefs-Kai 65, 1010 Wien';

    // Technik (Technical) Contact Information
    public string $technik_contactform_email = 'office@bereal-technik.at';

    public string $technik_contactform_phone = '+43 1 535 36 19';

    public string $technik_contactform_address = 'Franz-Josefs-Kai 65, 1010 Wien';

    // Contact Form Terms
    public string $contactform_terms_de = 'Ich bin mit der Verarbeitung und Speicherung meiner Daten, sowie mit der Kontaktaufnahme via E-Mail oder Telefon im Zuge der Bearbeitung meiner Anfrage einverstanden.';

    public string $contactform_terms_en = 'I agree to the processing and storage of my data, as well as to being contacted via email or telephone in the course of processing my inquiry.';

    // Contact Persons
    public string $contactpersons_heading_de = 'Ihre Ansprechpartner!';

    public string $contactpersons_heading_en = 'Your Contact Persons!';

    public string $contactpersons_introtext_de = 'Zusammenarbeit funktioniert am besten, wenn sie wissen, wer am anderen Ende der Leitung sitzt. Hier dürfen wir Ihnen die zuständigen MitarbeiterInnen vorstellen.';

    public string $contactpersons_introtext_en = 'Collaboration works best when you know who is on the other end of the line. Here we would like to introduce you to the responsible employees.';

    public static function group(): string
    {
        return 'translatable_contact';
    }
}
