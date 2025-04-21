<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class PagesSettings extends Settings
{

    public string $contact_header = 'together';

    public string $contact_subheader = 'Ihr Kontakt zu Bontus Eybel';

    public string $contact_introtext = '';


    public string $team_header = 'team';

    public string $team_subheader = 'Das Team von Bontus Eybel';

    public string $team_introtext = '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>';



    public string $contactform_heading = 'Sagen Sie uns Hallo!';

    public string $contactform_email = 'office@bontus-eybel.at';

    public string $contactform_phone = '+43 1 535 36 19';

    public string $contactform_address = 'Franz Josefs Kai 65, 1010 Wien';

    public string $contactform_terms = 'Ich bin mit der Verarbeitung und Speicherung meiner Daten, sowie mit der Kontaktaufnahme via E-Mail oder Telefon im Zuge der Bearbeitung meiner Anfrage einverstanden.';

    public string $contactpersons_heading = 'Ihre Ansprechpartner!';

    public string $contactpersons_introtext;

    public string $vacancies_introtext;

    public ?int $vacancies_image;

    public string $cold_application_cta_text;


    public static function group(): string
    {
        return 'pages';
    }
}