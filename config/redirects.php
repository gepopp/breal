<?php

/*
|--------------------------------------------------------------------------
| Permanent redirects for old addresses
|--------------------------------------------------------------------------
|
| "old path" or "old path?query" => "new path" or "new path?query". Both are
| answered with 301 by App\Http\Middleware\RedirectLegacyUrls; a language
| prefix ("/en") is kept. Add the addresses of the old website and anything
| that shows up as "not found" in Search Console here.
|
*/

return [

    'permanent' => [

        // The company story ("Why be real?", "Our Story") is on the start page.
        '/ueber-uns' => '/',

        // The section start pages lost their "/start" suffix.
        '/makler/start' => '/makler',
        '/technik/start' => '/technik',

        // Old Bontus Eybel website (bontus-eybel.at): the resident service page, indexed by Google, is now the service page.
        '/bewohnerservice' => '/service',

        // Addresses of the website before the relaunch that Bing still lists (Webmaster Tools, 2026-11-12).
        '/immobilien' => '/makler/immobiliensuche',
        '/immobilien/start' => '/makler',
        '/miete' => '/makler/immobiliensuche',
        '/verwalterservice' => '/unternehmensservice',
        '/ueber_uns' => '/',
        '/ueber_uns/team' => '/team',

        // Damage report of the old website; the form is a service now (Google Search Console, 2026-11-15).
        '/schadensmeldung' => '/service?tab=schadensformular',

        // Placeholder slugs from the seed data; renamed by a migration. Google still knows the address under every section.
        '/leistung/alias-qui-vel-ea-repellendus' => '/leistung/bauverwaltung',
        '/makler/leistung/alias-qui-vel-ea-repellendus' => '/leistung/bauverwaltung',
        '/technik/leistung/alias-qui-vel-ea-repellendus' => '/leistung/bauverwaltung',
        '/service?tab=aliquam-repudiandae-expedita-rerum' => '/service?tab=sepa-mandat',

    ],

];
