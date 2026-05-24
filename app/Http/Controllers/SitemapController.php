<?php

namespace App\Http\Controllers;

use App\Models\Competence;
use App\Models\JobVacancy;
use App\Models\Realty;
use Illuminate\Support\Facades\File;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function generate()
    {
        File::delete(public_path('sitemap.xml'));

        $sitemap = Sitemap::create();

        // Hausverwaltung routes
        $sitemap->add(Url::create(route('hausverwaltung.home')));
        $sitemap->add(Url::create(route('hausverwaltung.leistungen')));
        $sitemap->add(Url::create(route('hausverwaltung.service')));
        $sitemap->add(Url::create(route('hausverwaltung.karriere')));
        $sitemap->add(Url::create(route('hausverwaltung.kontakt')));
        $sitemap->add(Url::create(route('hausverwaltung.team')));
        $sitemap->add(Url::create(route('hausverwaltung.faq')));

        // Makler routes
        $sitemap->add(Url::create(route('makler.home')));
        $sitemap->add(Url::create(route('makler.immobiliensuche')));
        $sitemap->add(Url::create(route('makler.ueber-uns')));
        $sitemap->add(Url::create(route('makler.karriere')));
        $sitemap->add(Url::create(route('makler.kontakt')));
        $sitemap->add(Url::create(route('makler.team')));
        $sitemap->add(Url::create(route('makler.faq')));

        // Technik routes
        $sitemap->add(Url::create(route('technik.home')));
        $sitemap->add(Url::create(route('technik.karriere')));
        $sitemap->add(Url::create(route('technik.kontakt')));
        $sitemap->add(Url::create(route('technik.team')));
        $sitemap->add(Url::create(route('technik.faq')));
        $sitemap->add(Url::create(route('technik.leistungen')));

        // General routes
        $sitemap->add(Url::create(route('impressum')));
        $sitemap->add(Url::create(route('barrierefreiheit')));
        $sitemap->add(Url::create(route('datenschutz')));
        $sitemap->add(Url::create(route('verwalterservice')));

        // Add model routes
        $sitemap->add(Competence::all());
        $sitemap->add(JobVacancy::all());
        $sitemap->add(Realty::all());

        $sitemap->writeToFile(public_path('sitemap.xml'));
    }
}
