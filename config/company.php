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
    ],

    'address' => [
        'street' => 'Franz-Josefs-Kai 65/Mezzanin',
        'postal_code' => '1010',
        'city' => 'Wien',
        'country' => 'AT',
    ],

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

    'sections' => [

        'Hausverwaltung' => [
            'name' => 'be real Verwaltung',
            'legal_name' => 'be real Immobilienmanagement GmbH',
            'email' => 'office@bereal-immobilien.at',
            'website' => 'https://www.bereal-immobilien.at',
            'firmenbuch' => 'FN 152672 v',
            'uid' => 'ATU49490402',
            'managing_directors' => ['Lukas Eybel'],
        ],

        'Makler' => [
            'name' => 'be real Makler',
            'legal_name' => 'be real Immobilienmakler GmbH',
            'email' => 'office@bereal-makler.at',
            'website' => 'https://www.bereal-makler.at',
            'firmenbuch' => 'FN 556967 w',
            'uid' => 'ATU76971617',
            'managing_directors' => ['Lukas Eybel', 'Tanja Bachschwöller-Eybel'],
        ],

        'Technik' => [
            'name' => 'be real Technik',
            'legal_name' => 'be real Baumanagement GmbH',
            'email' => 'office@bereal-technik.at',
            'website' => 'https://www.bereal-technik.at',
            'firmenbuch' => 'FN 660246 a',
            'uid' => 'ATU82490109',
            'managing_directors' => ['Thomas Keinrath'],
        ],

    ],

];
