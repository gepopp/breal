<?php

namespace App\Services;

use App\Enums\CompaniesEnum;
use App\Models\Competence;
use App\Models\Contactperson;
use App\Models\Department;
use App\Models\JobVacancy;
use App\Models\Question;
use App\Models\Realty;
use App\Models\Reference;
use App\Models\Service;
use App\Models\ServiceCard;
use App\Models\Timeline;
use Illuminate\Support\Carbon;
use Spatie\LaravelSettings\Models\SettingsProperty;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

/**
 * Builds the sitemap: every page once, with the date its content last
 * changed and, where an English version exists, the language alternates.
 */
class SitemapBuilder
{
    /**
     * Static pages with the settings groups and models whose last change
     * decides their lastmod.
     *
     * @var array<string, array{groups: list<string>, models: list<class-string>}>
     */
    private const STATIC_PAGES = [
        'hausverwaltung.home' => ['groups' => ['translatable_hausverwaltung_landingpage'], 'models' => [Competence::class, Reference::class, Timeline::class, Service::class]],
        'hausverwaltung.leistungen' => ['groups' => ['translatable_leistungen'], 'models' => [Competence::class]],
        'hausverwaltung.service' => ['groups' => ['pages'], 'models' => [Service::class]],
        'hausverwaltung.karriere' => ['groups' => ['translatable_vacancies'], 'models' => [JobVacancy::class]],
        'hausverwaltung.kontakt' => ['groups' => ['translatable_contact', 'pages'], 'models' => [Department::class, Contactperson::class]],
        'hausverwaltung.team' => ['groups' => ['translatable_team'], 'models' => [Department::class, Contactperson::class]],
        'hausverwaltung.faq' => ['groups' => ['translatable_faq'], 'models' => [Question::class]],
        'verwalterservice' => ['groups' => ['translatable_verwalterservicelandingpage'], 'models' => [ServiceCard::class]],
        'makler.home' => ['groups' => ['translatable_makler'], 'models' => [Realty::class]],
        'makler.ueber-uns' => ['groups' => ['translatable_makler'], 'models' => []],
        'makler.immobiliensuche' => ['groups' => ['translatable_search'], 'models' => [Realty::class]],
        'makler.karriere' => ['groups' => ['translatable_vacancies'], 'models' => [JobVacancy::class]],
        'makler.kontakt' => ['groups' => ['translatable_contact', 'pages'], 'models' => [Department::class, Contactperson::class]],
        'makler.team' => ['groups' => ['translatable_team'], 'models' => [Department::class, Contactperson::class]],
        'makler.faq' => ['groups' => ['translatable_faq'], 'models' => [Question::class]],
        'technik.home' => ['groups' => ['translatable_technik'], 'models' => [Competence::class]],
        'technik.leistungen' => ['groups' => ['translatable_leistungen'], 'models' => [Competence::class]],
        'technik.karriere' => ['groups' => ['translatable_vacancies'], 'models' => [JobVacancy::class]],
        'technik.kontakt' => ['groups' => ['translatable_contact', 'pages'], 'models' => [Department::class, Contactperson::class]],
        'technik.team' => ['groups' => ['translatable_team'], 'models' => [Department::class, Contactperson::class]],
        'technik.faq' => ['groups' => ['translatable_faq'], 'models' => [Question::class]],
        'impressum' => ['groups' => ['translatable_legal', 'pages'], 'models' => []],
        'barrierefreiheit' => ['groups' => ['translatable_legal', 'pages'], 'models' => []],
        'datenschutz' => ['groups' => ['translatable_legal', 'pages'], 'models' => []],
    ];

    public function build(): Sitemap
    {
        $sitemap = Sitemap::create();

        foreach (self::STATIC_PAGES as $route => $sources) {
            $this->addTranslated($sitemap, route($route), $this->lastModified($sources['groups'], $sources['models']));
        }

        foreach (Competence::all() as $competence) {
            $this->addTranslated($sitemap, route($this->competenceRoute($competence), $competence), $competence->updated_at);
        }

        // The first service is the default tab, its canonical address is /service which is already listed.
        foreach (Service::all()->skip(1) as $service) {
            $this->addGerman($sitemap, $service->link, $service->updated_at);
        }

        foreach (Question::all() as $question) {
            $this->addGerman($sitemap, route('faq.single', $question->slug), $question->updated_at);
        }

        foreach (JobVacancy::query()->where('from', '<=', now())->where('to', '>=', now())->get() as $vacancy) {
            $this->addGerman($sitemap, route('stellenanzeige', $vacancy), $vacancy->updated_at);
        }

        foreach (Realty::all() as $realty) {
            $this->addGerman($sitemap, route('makler.immobilie', $realty), $realty->updated_at);
        }

        return $sitemap;
    }

    private function competenceRoute(Competence $competence): string
    {
        return match ($competence->company) {
            CompaniesEnum::Makler => 'makler.leistung',
            CompaniesEnum::Technik => 'technik.leistung',
            default => 'hausverwaltung.leistung',
        };
    }

    /**
     * Adds the German and the English address of a page, each listing both alternates.
     */
    private function addTranslated(Sitemap $sitemap, string $germanUrl, ?Carbon $lastModified): void
    {
        $englishUrl = $this->englishUrl($germanUrl);

        foreach ([$germanUrl, $englishUrl] as $url) {
            $sitemap->add(
                $this->url($url, $lastModified)
                    ->addAlternate($germanUrl, 'de-AT')
                    ->addAlternate($englishUrl, 'en')
                    ->addAlternate($germanUrl, 'x-default')
            );
        }
    }

    /**
     * Adds a page that only exists in German content.
     */
    private function addGerman(Sitemap $sitemap, string $url, ?Carbon $lastModified): void
    {
        $sitemap->add($this->url($url, $lastModified));
    }

    private function url(string $url, ?Carbon $lastModified): Url
    {
        $tag = Url::create($url);

        return $lastModified ? $tag->setLastModificationDate($lastModified) : $tag;
    }

    private function englishUrl(string $germanUrl): string
    {
        $parts = parse_url($germanUrl);

        return $parts['scheme'].'://'.$parts['host'].(isset($parts['port']) ? ':'.$parts['port'] : '')
            .LocaleUrls::prefixPath($parts['path'] ?? '/', 'en')
            .(isset($parts['query']) ? '?'.$parts['query'] : '');
    }

    /**
     * @param  list<string>  $groups
     * @param  list<class-string>  $models
     */
    private function lastModified(array $groups, array $models): ?Carbon
    {
        $dates = [SettingsProperty::query()->whereIn('group', $groups)->max('updated_at')];

        foreach ($models as $model) {
            $dates[] = $model::query()->max('updated_at');
        }

        $latest = collect($dates)->filter()->max();

        return $latest ? Carbon::parse($latest) : null;
    }
}
