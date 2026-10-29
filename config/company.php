<?php

/*
|--------------------------------------------------------------------------
| Company data
|--------------------------------------------------------------------------
|
| Single source for the facts that appear in the footer, the contact areas,
| e-mails, the Impressum and structured data. Source: briefing "PR und Social
| Media: Markenauftritt und Website" (26.10.2026). The sections are keyed by
| the names of App\Enums\CompaniesEnum.
|
*/

return [

    'group' => [
        'name' => 'be real Immobilien',
        'website' => 'https://www.bereal-immobilien.at',
        'logo' => 'logos/bereal_immobilien.svg',

        /*
        | Profile URLs (LinkedIn, Facebook, Instagram, ...) for the structured
        | data "sameAs". Empty until the profiles exist (delivery from Ronald).
        */
        'social_profiles' => [],
    ],

    'address' => [
        'street' => 'Franz-Josefs-Kai 65/Mezzanin',
        'postal_code' => '1010',
        'city' => 'Wien',
        'country' => 'AT',
    ],

    /*
    | The service lists only offer the search field from this many services on;
    | below it the filter is hidden (the code stays for a growing list).
    */

    'service_filter_min_items' => 10,

    'phone' => '+43 1 535 36 19',

    'fax' => '+43 1 535 64 28',

    /*
    | Times per day group, "from-to". Several ranges per group are possible.
    */

    'opening_hours' => [
        'mon_thu' => ['08:00-16:00'],
        'fri' => ['08:00-12:00'],
    ],

    'phone_hours' => [
        'mon_thu' => ['09:00-12:00', '13:00-16:00'],
        'fri' => ['09:00-12:00'],
    ],

    /*
    | Disclosure pursuant to § 25 MedienG for the website (Medieninhaber is the
    | Hausverwaltung company). Shown in the Impressum once filled in.
    */

    'media' => [
        'owners' => null,
        'editorial_line' => null,
    ],

    /*
    | Per company: "founded" (year of foundation, start of the copyright range,
    | empty = current year only), "firmenbuchgericht", "gewerbe", "kammer" and
    | "aufsichtsbehoerde" are shown in the Impressum as soon as they are filled
    | in (delivery from the client); empty values are not printed.
    */

    'sections' => [

        'Hausverwaltung' => [
            'name' => 'be real Verwaltung',
            'legal_name' => 'be real Immobilienmanagement GmbH',
            'email' => 'office@bereal-immobilien.at',
            'website' => 'https://www.bereal-immobilien.at',
            'logo' => 'logos/bereal_immobilien.svg',
            'founded' => 2006,
            'firmenbuch' => 'FN 152672 v',
            'uid' => 'ATU49490402',
            'managing_directors' => ['Lukas Eybel'],
            'firmenbuchgericht' => null,
            'gewerbe' => null,
            'kammer' => null,
            'aufsichtsbehoerde' => null,
        ],

        'Makler' => [
            'name' => 'be real Makler',
            'legal_name' => 'be real Immobilienmakler GmbH',
            'email' => 'office@bereal-makler.at',
            'website' => 'https://www.bereal-makler.at',
            'logo' => 'logos/bereal_makler.svg',
            'founded' => 2021,
            'firmenbuch' => 'FN 556967 w',
            'uid' => 'ATU76971617',
            'managing_directors' => ['Lukas Eybel', 'Tanja Bachschwöller-Eybel'],
            'firmenbuchgericht' => null,
            'gewerbe' => null,
            'kammer' => null,
            'aufsichtsbehoerde' => null,
        ],

        'Technik' => [
            'name' => 'be real Technik',
            'legal_name' => 'be real Baumanagement GmbH',
            'email' => 'office@bereal-technik.at',
            'website' => 'https://www.bereal-technik.at',
            'logo' => 'logos/bereal_technik.svg',
            'founded' => null,
            'firmenbuch' => 'FN 660246 a',
            'uid' => 'ATU82490109',
            'managing_directors' => ['Thomas Keinrath'],
            'firmenbuchgericht' => null,
            'gewerbe' => null,
            'kammer' => null,
            'aufsichtsbehoerde' => null,
        ],

    ],

];
