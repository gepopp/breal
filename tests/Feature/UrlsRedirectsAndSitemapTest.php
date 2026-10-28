<?php

namespace Tests\Feature;

use App\Enums\CompaniesEnum;
use App\Models\Realty;
use App\Models\Service;
use Database\Factories\CompetenceFactory;
use Database\Factories\JobVacancyFactory;
use Database\Factories\QuestionFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use SimpleXMLElement;
use Tests\TestCase;

class UrlsRedirectsAndSitemapTest extends TestCase
{
    use RefreshDatabase;

    private const NOT_FOUND_TITLE = '<title>Seite nicht gefunden – be real Immobilien</title>';

    public function test_unknown_address_returns_a_real_404_with_the_site_page(): void
    {
        $response = $this->get('/diese-seite-gibt-es-nicht');

        $response->assertNotFound();
        $response->assertSee(self::NOT_FOUND_TITLE, false);
        $response->assertSee('<meta name="robots" content="noindex">', false);
    }

    public function test_unknown_english_address_returns_a_404_in_english(): void
    {
        $this->get('/en/this-page-does-not-exist')
            ->assertNotFound()
            ->assertSee('<title>Page Not Found – be real Immobilien</title>', false);
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function missingContent(): array
    {
        return [
            'competence' => ['/leistung/gibt-es-nicht'],
            'makler competence' => ['/makler/leistung/gibt-es-nicht'],
            'faq' => ['/faq/gibt-es-nicht'],
            'job' => ['/stellenanzeige/gibt-es-nicht'],
            'property' => ['/makler/immobilie/gibt-es-nicht'],
        ];
    }

    #[DataProvider('missingContent')]
    public function test_missing_content_shows_the_site_404_page(string $path): void
    {
        $this->get($path)->assertNotFound()->assertSee(self::NOT_FOUND_TITLE, false);
    }

    public function test_expired_and_future_job_ads_are_not_found_and_active_ones_are_served(): void
    {
        $expired = JobVacancyFactory::new()->create(['from' => now()->subMonths(3), 'to' => now()->subMonth()]);
        $future = JobVacancyFactory::new()->create(['from' => now()->addMonth(), 'to' => now()->addMonths(3)]);
        $active = JobVacancyFactory::new()->create(['from' => now()->subDay(), 'to' => now()->addMonth()]);

        $this->get(route('stellenanzeige', $expired, false))->assertNotFound();
        $this->get(route('stellenanzeige', $future, false))->assertNotFound();
        $this->get(route('stellenanzeige', $active, false))->assertOk();
    }

    public function test_old_addresses_are_redirected_permanently(): void
    {
        $this->get('/ueber-uns')->assertStatus(301)->assertRedirect($this->root().'/');
        $this->get('/en/ueber-uns')->assertStatus(301)->assertRedirect($this->root().'/en');
        $this->get('/leistung/alias-qui-vel-ea-repellendus')->assertStatus(301)->assertRedirect($this->root().'/leistung/bauverwaltung');
        $this->get('/service?tab=aliquam-repudiandae-expedita-rerum')->assertStatus(301)->assertRedirect($this->root().'/service?tab=sepa-mandat');
    }

    public function test_unlisted_addresses_and_other_methods_are_not_redirected(): void
    {
        $this->get('/service?tab=sepa-mandat')->assertOk();
        $this->get('/ueber-uns-xyz')->assertNotFound();
        $this->post('/ueber-uns')->assertStatus(405);
    }

    public function test_a_competence_exists_only_in_its_own_section(): void
    {
        CompetenceFactory::new()->create(['company' => CompaniesEnum::Technik, 'name' => 'Bauwerksbuch Test', 'slug' => 'bauwerksbuch-test']);
        CompetenceFactory::new()->create(['company' => CompaniesEnum::Hausverwaltung, 'name' => 'Beratung Test', 'slug' => 'beratung-test']);

        $this->get('/technik/leistung/bauwerksbuch-test')->assertOk();
        $this->get('/leistung/bauwerksbuch-test')->assertStatus(301)->assertRedirect($this->root().'/technik/leistung/bauwerksbuch-test');
        $this->get('/makler/leistung/bauwerksbuch-test')->assertStatus(301)->assertRedirect($this->root().'/technik/leistung/bauwerksbuch-test');
        $this->get('/en/leistung/bauwerksbuch-test')->assertStatus(301)->assertRedirect($this->root().'/en/technik/leistung/bauwerksbuch-test');

        $this->get('/leistung/beratung-test')->assertOk();
        $this->get('/technik/leistung/beratung-test')->assertStatus(301)->assertRedirect($this->root().'/leistung/beratung-test');
    }

    public function test_migration_renames_the_placeholder_competence_slug(): void
    {
        CompetenceFactory::new()->create(['company' => CompaniesEnum::Hausverwaltung, 'name' => 'Bauverwaltung', 'slug' => 'alias-qui-vel-ea-repellendus']);

        $migration = require database_path('migrations/2026_10_28_205616_rename_lorem_ipsum_competence_slug.php');
        $migration->up();
        $migration->up();

        $this->get('/leistung/bauverwaltung')->assertOk();
        $this->get('/leistung/alias-qui-vel-ea-repellendus')->assertRedirect($this->root().'/leistung/bauverwaltung');
    }

    public function test_migration_leaves_the_slug_alone_when_the_new_one_is_taken(): void
    {
        CompetenceFactory::new()->create(['slug' => 'alias-qui-vel-ea-repellendus']);
        CompetenceFactory::new()->create(['slug' => 'bauverwaltung']);

        (require database_path('migrations/2026_10_28_205616_rename_lorem_ipsum_competence_slug.php'))->up();

        $this->assertDatabaseHas('competences', ['slug' => 'alias-qui-vel-ea-repellendus']);
    }

    public function test_empty_about_page_is_not_indexed(): void
    {
        $this->get('/makler/ueber-uns')->assertOk()->assertSee('<meta name="robots" content="noindex">', false);
    }

    public function test_sitemap_lists_every_page_once_with_lastmod_and_alternates(): void
    {
        $this->createSitemapContent();

        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $this->assertStringStartsWith('application/xml', $response->headers->get('Content-Type'));
        $this->assertFileDoesNotExist(public_path('sitemap.xml'));

        $xml = $this->sitemap($response->getContent());
        $locations = array_map('strval', $xml->xpath('//s:url/s:loc'));

        $this->assertSame(array_unique($locations), $locations, 'Duplicate addresses in the sitemap.');
        $this->assertCount(count($locations), $xml->xpath('//s:url/s:lastmod'), 'Every address needs a lastmod.');

        foreach (['/', '/en', '/kontakt', '/en/kontakt', '/makler/start', '/en/makler/start', '/technik/leistungen', '/datenschutz', '/unternehmensservice'] as $path) {
            $this->assertContains($this->root().($path === '/' ? '' : $path), $locations, $path);
        }

        foreach ($locations as $location) {
            $this->assertStringNotContainsString('/makler/ueber-uns', $location);
            $this->assertStringNotContainsString('/sitemap', $location);
        }
    }

    public function test_sitemap_lists_each_competence_once_under_its_own_section(): void
    {
        $this->createSitemapContent();

        $locations = array_map('strval', $this->sitemap($this->get('/sitemap.xml')->getContent())->xpath('//s:url/s:loc'));
        $competencePages = array_values(array_filter($locations, fn (string $location): bool => str_contains($location, '/leistung/')));

        sort($competencePages);

        $this->assertSame([
            $this->root().'/en/leistung/beratung-sitemap',
            $this->root().'/en/technik/leistung/bauwerksbuch-sitemap',
            $this->root().'/leistung/beratung-sitemap',
            $this->root().'/technik/leistung/bauwerksbuch-sitemap',
        ], $competencePages);
    }

    public function test_sitemap_contains_content_pages_but_only_active_job_ads(): void
    {
        $this->createSitemapContent();

        $locations = array_map('strval', $this->sitemap($this->get('/sitemap.xml')->getContent())->xpath('//s:url/s:loc'));

        $this->assertContains($this->root().'/service?tab=sitemap-service', $locations);
        $this->assertContains($this->root().'/faq/'.QuestionFactoryHelper::slug(), $locations);
        $this->assertContains($this->root().'/stellenanzeige/aktive-stelle-sitemap', $locations);
        $this->assertNotContains($this->root().'/stellenanzeige/abgelaufene-stelle-sitemap', $locations);
        $this->assertContains($this->root().'/makler/immobilie/wohnung-sitemap', $locations);
    }

    public function test_sitemap_declares_language_alternates(): void
    {
        $xml = $this->sitemap($this->get('/sitemap.xml')->getContent());

        $entry = $xml->xpath('//s:url[s:loc="'.$this->root().'/kontakt"]')[0];
        $entry->registerXPathNamespace('x', 'http://www.w3.org/1999/xhtml');
        $alternates = [];

        foreach ($entry->xpath('x:link') as $link) {
            $alternates[(string) $link['hreflang']] = (string) $link['href'];
        }

        $this->assertSame([
            'de-AT' => $this->root().'/kontakt',
            'en' => $this->root().'/en/kontakt',
            'x-default' => $this->root().'/kontakt',
        ], $alternates);
    }

    public function test_the_old_sitemap_generator_address_is_gone(): void
    {
        $this->get('/sitemap')->assertNotFound();
    }

    private function createSitemapContent(): void
    {
        CompetenceFactory::new()->create(['company' => CompaniesEnum::Technik, 'name' => 'Bauwerksbuch Sitemap', 'slug' => 'bauwerksbuch-sitemap']);
        CompetenceFactory::new()->create(['company' => CompaniesEnum::Hausverwaltung, 'name' => 'Beratung Sitemap', 'slug' => 'beratung-sitemap']);

        Service::withoutEvents(fn () => Service::create([
            'slug' => 'sitemap-service', 'order' => 1, 'company' => 'Hausverwaltung',
            'name' => ['de' => 'Sitemap Service', 'en' => 'Sitemap service'],
            'description' => ['de' => 'Beschreibung', 'en' => 'Description'], 'links' => [], 'list' => [],
        ]));

        QuestionFactory::new()->create(['company' => CompaniesEnum::Hausverwaltung, 'question' => QuestionFactoryHelper::QUESTION]);

        JobVacancyFactory::new()->create(['title' => 'Aktive Stelle Sitemap', 'slug' => 'aktive-stelle-sitemap', 'from' => now()->subDay(), 'to' => now()->addMonth()]);
        JobVacancyFactory::new()->create(['title' => 'Abgelaufene Stelle Sitemap', 'slug' => 'abgelaufene-stelle-sitemap', 'from' => now()->subMonths(3), 'to' => now()->subMonth()]);

        Realty::create(['title' => 'Wohnung Sitemap', 'slug' => 'wohnung-sitemap', 'data' => []]);
    }

    private function sitemap(string $content): SimpleXMLElement
    {
        $xml = new SimpleXMLElement($content);
        $xml->registerXPathNamespace('s', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        return $xml;
    }

    private function root(): string
    {
        return rtrim(config('app.url'), '/');
    }
}

class QuestionFactoryHelper
{
    public const QUESTION = 'Wie funktioniert die Sitemap';

    public static function slug(): string
    {
        return 'wie-funktioniert-die-sitemap';
    }
}
