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

        // Placeholder slugs from the seed data; renamed by a migration.
        '/leistung/alias-qui-vel-ea-repellendus' => '/leistung/bauverwaltung',
        '/service?tab=aliquam-repudiandae-expedita-rerum' => '/service?tab=sepa-mandat',

    ],

];
