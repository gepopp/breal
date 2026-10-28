<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnglishPrefixTest extends TestCase
{
    use RefreshDatabase;

    public function test_english_start_page_is_served_under_the_en_prefix(): void
    {
        $response = $this->get('/en');

        $response->assertOk();
        $response->assertSee('<html lang="en">', false);
        $response->assertSee($this->englishTranslation('facility_management.title'));
        $response->assertDontSee('facility_management.title');
    }

    public function test_trailing_slash_after_the_prefix_serves_the_same_page(): void
    {
        $this->get('/en/')->assertOk()->assertSee('<html lang="en">', false);
    }

    public function test_english_inner_page_is_served_under_the_en_prefix(): void
    {
        $response = $this->get('/en/kontakt');

        $response->assertOk();
        $response->assertSee('<html lang="en">', false);
        $response->assertDontSee('<html lang="de-AT">', false);
    }

    public function test_links_on_english_pages_keep_the_prefix(): void
    {
        $response = $this->get('/en/kontakt');

        $response->assertSee('href="'.$this->root().'/en/makler/start"', false);
        $response->assertSee('href="'.$this->root().'/en/technik/start"', false);
        $response->assertSee('href="'.$this->root().'/en/impressum"', false);
        $response->assertDontSee('href="'.$this->root().'/makler/start"', false);
    }

    public function test_links_on_german_pages_have_no_prefix(): void
    {
        $response = $this->get('/kontakt');

        $response->assertSee('href="'.$this->root().'/makler/start"', false);
        $response->assertDontSee('href="'.$this->root().'/en/makler/start"', false);
    }

    public function test_german_pages_declare_hreflang_alternates_and_a_self_canonical(): void
    {
        $response = $this->get('/kontakt');

        $response->assertSee('<link rel="canonical" href="'.$this->root().'/kontakt">', false);
        $response->assertSee('<link rel="alternate" hreflang="de-AT" href="'.$this->root().'/kontakt">', false);
        $response->assertSee('<link rel="alternate" hreflang="en" href="'.$this->root().'/en/kontakt">', false);
        $response->assertSee('<link rel="alternate" hreflang="x-default" href="'.$this->root().'/kontakt">', false);
    }

    public function test_english_pages_declare_hreflang_alternates_and_a_self_canonical(): void
    {
        $response = $this->get('/en/kontakt');

        $response->assertSee('<link rel="canonical" href="'.$this->root().'/en/kontakt">', false);
        $response->assertSee('<link rel="alternate" hreflang="de-AT" href="'.$this->root().'/kontakt">', false);
        $response->assertSee('<link rel="alternate" hreflang="en" href="'.$this->root().'/en/kontakt">', false);
        $response->assertSee('<link rel="alternate" hreflang="x-default" href="'.$this->root().'/kontakt">', false);
    }

    public function test_start_pages_map_to_the_root_and_en(): void
    {
        $this->get('/en')->assertSee('<link rel="canonical" href="'.$this->root().'/en">', false)
            ->assertSee('<link rel="alternate" hreflang="de-AT" href="'.$this->root().'">', false);

        $this->get('/')->assertSee('<link rel="alternate" hreflang="en" href="'.$this->root().'/en">', false);
    }

    public function test_paths_that_only_start_with_en_are_not_treated_as_english(): void
    {
        $response = $this->get('/entwicklung');

        $response->assertSee('<html lang="de-AT">', false);
        $response->assertDontSee('hreflang', false);
    }

    public function test_unknown_english_path_falls_back_to_the_not_found_page_in_english(): void
    {
        $response = $this->get('/en/does-not-exist');

        $response->assertSee('<html lang="en">', false);
        $response->assertSee($this->englishTranslation('unfound'));
    }

    public function test_only_public_page_routes_get_the_prefix_when_generating_urls(): void
    {
        $this->get('/en/kontakt');

        $this->assertSame($this->root().'/en/makler/start', route('makler.home'));
        $this->assertSame($this->root().'/en', route('hausverwaltung.home'));
        $this->assertSame($this->root().'/en/leistung/x', route('hausverwaltung.leistung', 'x'));
        $this->assertStringNotContainsString('/en/', route('solve', ['request' => 1, 'token' => 'abc']));
        $this->assertStringNotContainsString('/en/', route('confirm', ['id' => 1, 'token' => 'abc']));
    }

    public function test_session_locale_does_not_switch_the_public_german_pages(): void
    {
        $response = $this->withSession(['locale' => 'en'])->get('/kontakt');

        $response->assertSee('<html lang="de-AT">', false);
    }

    public function test_lang_query_parameter_does_not_switch_the_language(): void
    {
        $this->get('/?lang=en')->assertSee('<html lang="de-AT">', false);
    }

    public function test_livewire_updates_from_english_pages_stay_english_with_prefixed_links(): void
    {
        $page = $this->get('/en/kontakt');

        preg_match('/data-update-uri="([^"]+)"/', $page->getContent(), $uri);
        preg_match_all('/wire:snapshot="([^"]+)"/', $page->getContent(), $snapshots);

        $footerSnapshot = collect($snapshots[1])
            ->map(fn (string $snapshot): string => html_entity_decode($snapshot))
            ->first(fn (string $snapshot): bool => str_contains($snapshot, '"name":"footer"'));

        $this->assertNotNull($footerSnapshot);

        $update = $this->withHeaders(['X-Livewire' => 'true', 'Referer' => $this->root().'/en/kontakt'])
            ->postJson($uri[1], ['components' => [[
                'snapshot' => $footerSnapshot,
                'updates' => (object) [],
                'calls' => [['path' => '', 'method' => '$refresh', 'params' => []]],
            ]]]);

        $update->assertOk();
        $update->assertSee('Phone');
        $update->assertSee($this->jsonRoot().'\/en\/makler\/start', false);
    }

    private function root(): string
    {
        return rtrim(config('app.url'), '/');
    }

    private function jsonRoot(): string
    {
        return str_replace('/', '\/', $this->root());
    }

    private function englishTranslation(string $key): string
    {
        $translations = json_decode(file_get_contents(lang_path('en.json')), true, flags: JSON_THROW_ON_ERROR);

        return $translations[$key];
    }
}
