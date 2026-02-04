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

    public string $makler_contactform_email = 'office@bontus-eybel.at';

    public string $makler_contactform_phone = '+43 1 535 36 19';

    public string $makler_contactform_address = 'Franz Josefs Kai 65, 1010 Wien';

    public string $technik_contactform_email = 'office@bontus-eybel.at';

    public string $technik_contactform_phone = '+43 1 535 36 19';

    public string $technik_contactform_address = 'Franz Josefs Kai 65, 1010 Wien';

    public string $contactform_terms = 'Ich bin mit der Verarbeitung und Speicherung meiner Daten, sowie mit der Kontaktaufnahme via E-Mail oder Telefon im Zuge der Bearbeitung meiner Anfrage einverstanden.';

    public string $contactpersons_heading = 'Ihre Ansprechpartner!';

    public string $contactpersons_introtext;

    public string $cold_application_cta_text;

    public string $imprint_text = '<p>Impressum</p>';

    public string $dpgr_text = '<p>Datenschutz</p>';

    public string $accessability_text = '<p>Barrierefreiheit</p>';

    public string $services_header = 'secure';

    public string $services_subheader = 'Unser Versprechen';

    public string $services_introtext = '<p>Oft muss es schnell und einfach gehen - hier finden Sie alle wichtigen Unterlagen sowie nützliche Tipps für einen reibungslosen und effizienten Betrieb Ihres Gebäudes.</p>';

    public string $faq_header = 'informed';

    public string $faq_subheader = 'Antworten auf häufig gestellte Fragen';

    public string $faq_introtext = '<p>Hausverwaltung ist ein komplexes Thema. Da kann es schon mal zu Fragen kommen. Wir beantworten Ihre Fragen rund um das Thema Immobilienmanagement und Hausverwaltung.</p>';

    public string $hausverwaltung_leistungen_header = 'competent';

    public string $hausverwaltung_leistungen_subheader = 'Unsere Leistungen';

    public string $hausverwaltung_leistungen_introtext = '<p>Wir brennen für Hausverwaltung - von Wohneigentumsverwaltung und Mietrechtsgesetz bis zu Bauverwaltung und allgemeiner Beratung – wir kümmern uns darum, dass Sie sich rundum gut betreut fühlen. Dabei stehen partnerschaftliche Zusammenarbeit, individuelle Lösungen und umfangreiche Beratung an erster Stelle.</p>';

    public string $immobilien_leistungen_header = 'competent';

    public string $immobilien_leistungen_subheader = 'Unsere Leistungen';

    public string $immobilien_leistungen_introtext = '<p>Wir brennen für Hausverwaltung - von Wohneigentumsverwaltung und Mietrechtsgesetz bis zu Bauverwaltung und allgemeiner Beratung – wir kümmern uns darum, dass Sie sich rundum gut betreut fühlen. Dabei stehen partnerschaftliche Zusammenarbeit, individuelle Lösungen und umfangreiche Beratung an erster Stelle.</p>';

    public string $technik_leistungen_header = 'competent';

    public string $technik_leistungen_subheader = 'Unsere Leistungen';

    public string $technik_leistungen_introtext = '<p>Wir brennen für Hausverwaltung - von Wohneigentumsverwaltung und Mietrechtsgesetz bis zu Bauverwaltung und allgemeiner Beratung – wir kümmern uns darum, dass Sie sich rundum gut betreut fühlen. Dabei stehen partnerschaftliche Zusammenarbeit, individuelle Lösungen und umfangreiche Beratung an erster Stelle.</p>';

    public string $search_header = 'home';

    public string $search_subheader = 'Immobiliensuche der be real makler';

    public string $search_introtext = '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet hic incidunt, inventore ipsa iste minima nemo officia similique veniam vero. Aliquid aspernatur cumque dolore, eveniet facilis iusto laborum nihil similique?</p>';

    public static function group(): string
    {
        return 'pages';
    }

    public function toArray(): array
    {
        return [
            'contact_header' => $this->contact_header,
            'contact_subheader' => $this->contact_subheader,
            'contact_introtext' => $this->contact_introtext,
            'team_header' => $this->team_header,
            'team_subheader' => $this->team_subheader,
            'team_introtext' => $this->team_introtext,
            'vacancies_header' => $this->vacancies_header,
            'vacancies_subheader' => $this->vacancies_subheader,
            'vacancies_introtext' => $this->vacancies_introtext,
            'contactform_heading' => $this->contactform_heading,
            'contactform_email' => $this->contactform_email,
            'contactform_phone' => $this->contactform_phone,
            'contactform_address' => $this->contactform_address,
            'makler_contactform_email' => $this->makler_contactform_email,
            'makler_contactform_phone' => $this->makler_contactform_phone,
            'makler_contactform_address' => $this->makler_contactform_address,
            'technik_contactform_email' => $this->technik_contactform_email,
            'technik_contactform_phone' => $this->technik_contactform_phone,
            'technik_contactform_address' => $this->technik_contactform_address,
            'contactform_terms' => $this->contactform_terms,
            'contactpersons_heading' => $this->contactpersons_heading,
            'contactpersons_introtext' => $this->contactpersons_introtext,
            'cold_application_cta_text' => $this->cold_application_cta_text,
            'imprint_text' => $this->imprint_text,
            'dpgr_text' => $this->dpgr_text,
            'accessability_text' => $this->accessability_text,
            'services_header' => $this->services_header,
            'services_subheader' => $this->services_subheader,
            'services_introtext' => $this->services_introtext,
            'faq_header' => $this->faq_header,
            'faq_subheader' => $this->faq_subheader,
            'faq_introtext' => $this->faq_introtext,
            'hausverwaltung_leistungen_header' => $this->hausverwaltung_leistungen_header,
            'hausverwaltung_leistungen_subheader' => $this->hausverwaltung_leistungen_subheader,
            'hausverwaltung_leistungen_introtext' => $this->hausverwaltung_leistungen_introtext,
            'immobilien_leistungen_header' => $this->immobilien_leistungen_header,
            'immobilien_leistungen_subheader' => $this->immobilien_leistungen_subheader,
            'immobilien_leistungen_introtext' => $this->immobilien_leistungen_introtext,
            'technik_leistungen_header' => $this->technik_leistungen_header,
            'technik_leistungen_subheader' => $this->technik_leistungen_subheader,
            'technik_leistungen_introtext' => $this->technik_leistungen_introtext,
            'search_header' => $this->search_header,
            'search_subheader' => $this->search_subheader,
            'search_introtext' => $this->search_introtext,
        ];
    }
}
