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


    public string $vacancies_header = 'with us';

    public string $vacancies_subheader = 'Werde Teil des BeReal Teams';

    public string $vacancies_introtext = '<p>
                be real bietet ihnen nicht nur eine berufliche Herausforderung, sondern
                eine Gemeinschaft, die für Teamgeist und Zusammenarbeit steht. Die
                Arbeit in der Hausverwaltung ist ein vielseitiger Job, der aufregend und
                erfüllend ist. Jeder Tag ist anders und bringt viele neue Wege, sich
                kreativ auszuleben. Ein Job in der Immobilienverwaltung bedeutet
                Problemlöser, Kratzbürste und Kummerkasten in einem zu sein.
                Menschenkenntnis und die Leidenschaft für Kundennähe sind uns
                deshalb besonders wichtig.
            </p><p>
                Klingt spannend? Entdecken Sie unsere Möglichkeiten, um in einem
                inspirierenden Umfeld zu wachsen und die Immobilienbranche aktiv
                mitzugestalten.
            </p>';


    public string $contactform_heading = 'Sagen Sie uns Hallo!';

    public string $contactform_email = 'office@bontus-eybel.at';

    public string $contactform_phone = '+43 1 535 36 19';

    public string $contactform_address = 'Franz Josefs Kai 65, 1010 Wien';

    public string $contactform_terms = 'Ich bin mit der Verarbeitung und Speicherung meiner Daten, sowie mit der Kontaktaufnahme via E-Mail oder Telefon im Zuge der Bearbeitung meiner Anfrage einverstanden.';

    public string $contactpersons_heading = 'Ihre Ansprechpartner!';

    public string $contactpersons_introtext;

    public string $cold_application_cta_text;

    public string $imprint_text = "<p>Impressum</p>";

    public string $dpgr_text = "<p>Datenschutz</p>";

    public string $accessability_text = "<p>Barrierefreiheit</p>";


    public string $services_header = 'secure';

    public string $services_subheader = 'Unser Versprechen';

    public string $services_introtext = '<p>Oft muss es schnell und einfach gehen - hier finden Sie alle wichtigen Unterlagen sowie nützliche Tipps für einen reibungslosen und effizienten Betrieb Ihres Gebäudes.</p>';

    public string $faq_header = 'informed';

    public string $faq_subheader = 'Antworten auf häufig gestellte Fragen';

    public string $faq_introtext = '<p>Hausverwaltung ist ein komplexes Thema. Da kann es schon mal zu Fragen kommen. Wir beantworten Ihre Fragen rund um das Thema Immobilienmanagement und Hausverwaltung.</p>';

    public static function group(): string
    {
        return 'pages';
    }
}