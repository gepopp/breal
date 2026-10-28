<?php

namespace Tests\Feature;

use App\Enums\CompaniesEnum;
use App\Services\PageMeta;
use Database\Factories\CompetenceFactory;
use Database\Factories\JobVacancyFactory;
use Database\Factories\QuestionFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PageMetaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Public pages that render without database content.
     *
     * @return list<string>
     */
    private static function staticPaths(): array
    {
        return [
            '/', '/leistungen', '/service', '/karriere', '/kontakt', '/team', '/faq', '/unternehmensservice',
            '/makler/start', '/makler/ueber-uns', '/makler/immobiliensuche', '/makler/karriere', '/makler/kontakt', '/makler/team', '/makler/faq',
            '/technik/start', '/technik/karriere', '/technik/kontakt', '/technik/team', '/technik/faq', '/technik/leistungen',
            '/impressum', '/barrierefreiheit', '/datenschutz',
        ];
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function pages(): array
    {
        $pages = [];

        foreach (self::staticPaths() as $path) {
            $pages['de '.$path] = [$path];
            $pages['en '.$path] = [$path === '/' ? '/en' : '/en'.$path];
        }

        return $pages;
    }

    #[DataProvider('pages')]
    public function test_every_public_page_has_complete_meta_data(string $path): void
    {
        $response = $this->get($path);

        $response->assertOk();

        $meta = $this->metaOf($response);

        $this->assertNotSame('', $meta['title']);
        $this->assertLessThanOrEqual(65, mb_strlen($meta['title']), "Title too long on {$path}: {$meta['title']}");
        $this->assertGreaterThanOrEqual(10, mb_strlen($meta['title']));
        $this->assertNotSame('bereal', $meta['title']);
        $this->assertSame(1, $meta['descriptionTags'], "Exactly one description tag expected on {$path}.");
        $this->assertGreaterThanOrEqual(50, mb_strlen($meta['description']), "Description too short on {$path}.");
        $this->assertLessThanOrEqual(160, mb_strlen($meta['description']), "Description too long on {$path}.");
        $this->assertSame(1, $meta['h1'], "Exactly one h1 expected on {$path}.");
        $this->assertFalse($meta['keywords'], "Meta keywords must be gone on {$path}.");
        $this->assertSame($meta['title'], $meta['ogTitle']);
        $this->assertSame($meta['description'], $meta['ogDescription']);
        $this->assertSame($meta['canonical'], $meta['ogUrl']);
        $this->assertSame('website', $meta['ogType']);
        $this->assertSame('summary_large_image', $meta['twitterCard']);
        $this->assertStringContainsString('/og-', $meta['ogImage']);
    }

    public function test_titles_and_descriptions_are_unique_per_language(): void
    {
        foreach (['de' => '', 'en' => '/en'] as $language => $prefix) {
            $titles = [];
            $descriptions = [];

            foreach (self::staticPaths() as $path) {
                $uri = $prefix.($path === '/' ? ($prefix === '' ? '/' : '') : $path);
                $meta = $this->metaOf($this->get($uri));

                $titles[$meta['title']][] = $uri;
                $descriptions[$meta['description']][] = $uri;
            }

            $this->assertSame([], array_filter($titles, fn (array $uris): bool => count($uris) > 1), "Duplicate {$language} titles.");
            $this->assertSame([], array_filter($descriptions, fn (array $uris): bool => count($uris) > 1), "Duplicate {$language} descriptions.");
        }
    }

    public function test_section_pages_use_their_own_section_texts(): void
    {
        $this->assertSame('Team – be real Immobilien', $this->metaOf($this->get('/team'))['title']);
        $this->assertSame('Team – be real Makler', $this->metaOf($this->get('/makler/team'))['title']);
        $this->assertSame('Team – be real Technik', $this->metaOf($this->get('/technik/team'))['title']);
        $this->assertSame('be real Makler – Immobilien kaufen, verkaufen und mieten', $this->metaOf($this->get('/makler/start'))['title']);
        $this->assertSame('be real Technik – Baumanagement in Wien', $this->metaOf($this->get('/technik/start'))['title']);
        $this->assertSame('Team – be real Makler', $this->metaOf($this->get('/en/makler/team'))['title']);
    }

    public function test_home_page_uses_the_proposed_title_and_description(): void
    {
        $meta = $this->metaOf($this->get('/'));

        $this->assertSame('be real Immobilien – Hausverwaltung, Makler und Technik in Wien', $meta['title']);
        $this->assertSame('be real Immobilien verwaltet, vermittelt und betreut Immobilien in Wien. Seit fast 30 Jahren, persönlich und in festen Teams.', $meta['description']);
    }

    public function test_share_image_matches_the_section(): void
    {
        $this->assertStringEndsWith('/og-image.png', $this->metaOf($this->get('/'))['ogImage']);
        $this->assertStringEndsWith('/og-makler.png', $this->metaOf($this->get('/makler/team'))['ogImage']);
        $this->assertStringEndsWith('/og-technik.png', $this->metaOf($this->get('/technik/team'))['ogImage']);
        $this->get('/makler/team')->assertSee('content="Logo be real Makler"', false);
        $this->get('/en')->assertSee('content="en_GB"', false);
        $this->get('/')->assertSee('content="de_AT"', false);
    }

    public function test_not_found_page_has_its_own_title_h1_and_is_not_indexed(): void
    {
        $response = $this->get('/diese-seite-gibt-es-nicht');
        $meta = $this->metaOf($response);

        $this->assertSame('Seite nicht gefunden – be real Immobilien', $meta['title']);
        $this->assertSame(1, $meta['h1']);
        $response->assertSee('<meta name="robots" content="noindex">', false);
        $this->get('/kontakt')->assertDontSee('name="robots"', false);
    }

    public function test_competence_page_builds_title_and_description_from_its_content(): void
    {
        $competence = CompetenceFactory::new()->create([
            'company' => CompaniesEnum::Hausverwaltung,
            'name' => 'Allgemeine Beratung',
            'description' => '<p>Immobilien sind ein komplexes Thema.</p><p>'.str_repeat('Gut, dass wir helfen. ', 20).'</p>',
        ]);

        $meta = $this->metaOf($this->get(route('hausverwaltung.leistung', $competence, false)));

        $this->assertSame('Allgemeine Beratung – be real Verwaltung', $meta['title']);
        $this->assertStringStartsWith('Immobilien sind ein komplexes Thema. Gut, dass wir helfen.', $meta['description']);
        $this->assertLessThanOrEqual(155, mb_strlen($meta['description']));
        $this->assertStringNotContainsString('<', $meta['description']);
        $this->assertSame(1, $meta['h1']);
    }

    public function test_faq_detail_page_builds_title_and_description_from_the_question(): void
    {
        $question = QuestionFactory::new()->create([
            'company' => CompaniesEnum::Hausverwaltung,
            'question' => 'Wie wechsle ich die Hausverwaltung?',
            'answer' => '<p>Der Wechsel der Hausverwaltung erfolgt in wenigen Schritten und wird von uns begleitet.</p>',
        ]);

        $meta = $this->metaOf($this->get(route('faq.single', $question->slug, false)));

        $this->assertSame('Wie wechsle ich die Hausverwaltung? – be real Immobilien', $meta['title']);
        $this->assertSame('Der Wechsel der Hausverwaltung erfolgt in wenigen Schritten und wird von uns begleitet.', $meta['description']);
        $this->assertSame(1, $meta['h1']);
    }

    public function test_job_page_uses_the_career_suffix_and_a_plain_text_description(): void
    {
        $job = JobVacancyFactory::new()->create([
            'title' => 'Immobilienverwalter:in (m/w/d) mit Teamleitung',
            'description' => "<p>Ihre zukünftigen Tätigkeiten:</p>\n<ul><li>Leitung eines Teams</li><li>Betreuung von Objekten</li></ul>",
            'from' => now()->subDay(),
            'to' => now()->addMonth(),
        ]);

        $meta = $this->metaOf($this->get(route('stellenanzeige', $job, false)));

        $this->assertStringStartsWith('Immobilienverwalter:in (m/w/d) mit Teamleitung', $meta['title']);
        $this->assertLessThanOrEqual(60, mb_strlen($meta['title']));
        $this->assertSame('Ihre zukünftigen Tätigkeiten: Leitung eines Teams Betreuung von Objekten', $meta['description']);
        $this->assertSame(1, $meta['h1']);
    }

    public function test_page_meta_composes_titles_within_the_limit(): void
    {
        $this->assertSame('Kontakt – be real Makler', PageMeta::compose('Kontakt', 'be real Makler'));

        $long = 'Verwaltung einzelner Wohnungen oder Wohnungseigentumspakete';
        $composed = PageMeta::compose($long, 'be real Verwaltung');

        $this->assertLessThanOrEqual(PageMeta::TITLE_LIMIT, mb_strlen($composed));
        $this->assertStringStartsWith('Verwaltung einzelner Wohnungen', $composed);
        $this->assertStringNotContainsString('be real Verwaltung', $composed);
    }

    public function test_page_meta_turns_shouting_titles_into_title_case(): void
    {
        $this->assertSame('Hardtmuthgasse - Voll Ausgestattete Wohnung', PageMeta::normalizeTitle("HARDTMUTHGASSE -  VOLL AUSGESTATTETE\nWOHNUNG"));
        $this->assertSame('Immobilienverwalter:in (m/w/d)', PageMeta::normalizeTitle('Immobilienverwalter:in (m/w/d)'));
    }

    public function test_page_meta_excerpt_strips_tags_and_cuts_at_a_word_boundary(): void
    {
        $excerpt = PageMeta::excerpt('<p>Erster Absatz.</p><p>'.str_repeat('Wort ', 60).'</p>');

        $this->assertLessThanOrEqual(PageMeta::DESCRIPTION_LIMIT, mb_strlen($excerpt));
        $this->assertStringStartsWith('Erster Absatz. Wort Wort', $excerpt);
        $this->assertStringEndsWith('Wort…', $excerpt);
        $this->assertSame('', PageMeta::excerpt(null));
    }

    public function test_favicon_and_share_images_are_real_images(): void
    {
        $ico = file_get_contents(public_path('favicon.ico'));

        $this->assertGreaterThan(100, strlen($ico));
        $this->assertSame([0, 1], array_values(array_slice(unpack('v3', $ico), 0, 2)));

        $this->assertSame([180, 180], array_slice(getimagesize(public_path('apple-touch-icon.png')), 0, 2));

        foreach (['og-image.png', 'og-makler.png', 'og-technik.png'] as $image) {
            $this->assertSame([1200, 630], array_slice(getimagesize(public_path($image)), 0, 2), $image);
        }
    }

    public function test_head_links_the_favicons_and_theme_color(): void
    {
        $response = $this->get('/');

        $response->assertSee('<link rel="icon" href="'.url('favicon.ico').'" sizes="any">', false);
        $response->assertSee('<link rel="apple-touch-icon" href="'.url('apple-touch-icon.png').'">', false);
        $response->assertSee('<meta name="theme-color" content="#002540">', false);
    }

    public function test_legal_headings_no_longer_contain_the_typo(): void
    {
        $this->get('/impressum')->assertSee('be real Immobilienmanagement GmbH')->assertDontSee('Immobilienmanagment');
        $this->get('/barrierefreiheit')->assertSee('Barrierefreiheitserklärung der be real Immobilienmanagement GmbH')->assertDontSee('Immobilienmanagment');
    }

    /**
     * @return array<string, mixed>
     */
    private function metaOf(TestResponse $response): array
    {
        $html = $response->getContent();

        $value = function (string $pattern) use ($html): string {
            return preg_match($pattern, $html, $match) === 1 ? html_entity_decode($match[1], ENT_QUOTES | ENT_HTML5) : '';
        };

        return [
            'title' => $value('#<title>(.*?)</title>#s'),
            'description' => $value('#<meta name="description" content="(.*?)">#s'),
            'descriptionTags' => preg_match_all('#<meta name="description"#', $html),
            'h1' => preg_match_all('#<h1\b#', $html),
            'keywords' => str_contains($html, 'name="keywords"'),
            'canonical' => $value('#<link rel="canonical" href="(.*?)">#'),
            'ogTitle' => $value('#<meta property="og:title" content="(.*?)">#s'),
            'ogDescription' => $value('#<meta property="og:description" content="(.*?)">#s'),
            'ogUrl' => $value('#<meta property="og:url" content="(.*?)">#'),
            'ogType' => $value('#<meta property="og:type" content="(.*?)">#'),
            'ogImage' => $value('#<meta property="og:image" content="(.*?)">#'),
            'twitterCard' => $value('#<meta name="twitter:card" content="(.*?)">#'),
        ];
    }
}
