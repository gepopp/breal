<?php

namespace App\Http\Controllers;

use App\Models\Competence;
use App\Models\FAQ;
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
        $sitemap->add(Url::create(route('makler.leistungen')));

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

        // Competence routes for each company
        Competence::query()->get()->each(function (Competence $competence) use ($sitemap) {
            // Add for hausverwaltung
            $sitemap->add(Url::create(route('hausverwaltung.leistung', $competence)));

            // Add for makler
            $sitemap->add(Url::create(route('makler.leistung', $competence)));

            // Add for technik
            $sitemap->add(Url::create(route('technik.leistung', $competence)));
        });

        // FAQ routes
        FAQ::query()->get()->each(function (FAQ $faq) use ($sitemap) {
            $sitemap->add(Url::create(route('faq.single', $faq->slug)));
        });

        // Job Vacancy routes
        JobVacancy::query()->get()->each(function (JobVacancy $jobVacancy) use ($sitemap) {
            $sitemap->add(Url::create(route('stellenanzeige', $jobVacancy)));
        });

        // Realty routes
        Realty::query()->get()->each(function (Realty $realty) use ($sitemap) {
            $sitemap->add(Url::create(route('makler.immobilie', $realty)));
        });

        $sitemap->writeToFile(public_path('sitemap.xml'));
    }
}
