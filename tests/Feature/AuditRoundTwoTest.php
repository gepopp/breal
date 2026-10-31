<?php

namespace Tests\Feature;

use App\Livewire\Realty\Spaces;
use App\Models\Realty;
use App\Models\Service;
use App\Services\PageMeta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class AuditRoundTwoTest extends TestCase
{
    use RefreshDatabase;

    private function realty(array $attributes): Realty
    {
        return (new Realty)->forceFill($attributes + ['data' => []]);
    }

    public function test_title_wrapped_in_typographic_quotes_stays_valid_utf8_without_stray_characters(): void
    {
        $title = PageMeta::realtyTitle($this->realty([
            'title' => '„Altbaucharme trifft Moderne“ - Dr.-Karl-Lueger-Platz - 3,5 Zimmer Wohnung',
            'objektnr_extern' => '5883/743',
        ]));

        $this->assertTrue(mb_check_encoding($title, 'UTF-8'));
        $this->assertStringStartsWith('Altbaucharme trifft Moderne', $title);
        $this->assertStringNotContainsString('?', $title);
        $this->assertStringContainsString('Nr. 5883/743', $title);
        $this->assertLessThanOrEqual(PageMeta::TITLE_LIMIT, mb_strlen($title));
    }

    public function test_truncation_never_splits_a_multibyte_character(): void
    {
        $title = PageMeta::compose(str_repeat('Größenwahn – ', 10));

        $this->assertTrue(mb_check_encoding($title, 'UTF-8'));
        $this->assertStringEndsWith('…', $title);
    }

    public function test_description_uses_the_singular_for_one_room(): void
    {
        app()->setLocale('en');

        $one = PageMeta::realtyDescription($this->realty(['title' => 'Apartment', 'zimmer' => 1, 'ort' => 'Wien', 'objektnr_extern' => '1']), '<p>Text</p>');
        $several = PageMeta::realtyDescription($this->realty(['title' => 'Apartment', 'zimmer' => 3, 'ort' => 'Wien', 'objektnr_extern' => '1']), '<p>Text</p>');

        $this->assertStringStartsWith('1 Room ·', $one);
        $this->assertStringStartsWith('3 Rooms ·', $several);
    }

    public function test_living_area_is_backfilled_from_the_feed_data(): void
    {
        Realty::create(['title' => 'Mit Fläche', 'slug' => 'mit-flaeche', 'data' => ['flaechen' => ['wohnflaeche' => '33.31']]]);
        Realty::create(['title' => 'Ohne Fläche', 'slug' => 'ohne-flaeche', 'data' => []]);
        Realty::create(['title' => 'Schon gesetzt', 'slug' => 'schon-gesetzt', 'wohnflaeche' => 50, 'data' => ['flaechen' => ['wohnflaeche' => '99.99']]]);

        (require database_path('migrations/2026_11_01_100000_backfill_realty_living_area.php'))->up();

        $this->assertEquals(33.31, DB::table('realties')->where('slug', 'mit-flaeche')->value('wohnflaeche'));
        $this->assertNull(DB::table('realties')->where('slug', 'ohne-flaeche')->value('wohnflaeche'));
        $this->assertEquals(50, DB::table('realties')->where('slug', 'schon-gesetzt')->value('wohnflaeche'));
    }

    public function test_property_spaces_use_the_decimal_format_of_the_page_language(): void
    {
        app()->setLocale('de');
        Livewire::test(Spaces::class, ['spaces' => ['wohnflaeche' => '33.31', 'anzahl_zimmer' => '3.5']])
            ->assertSee('33,31 m²')
            ->assertSee('3,5');

        app()->setLocale('en');
        Livewire::test(Spaces::class, ['spaces' => ['wohnflaeche' => '33.31']])->assertSee('33.31 m²');
    }

    public function test_pagination_summary_is_translated_to_german(): void
    {
        app()->setLocale('de');

        $this->assertSame('Zeige', __('Showing'));
        $this->assertSame('Ergebnissen', __('results'));
    }

    public function test_property_search_reset_is_a_button_and_sort_headers_expose_their_state(): void
    {
        Realty::create(['title' => 'Wohnung', 'slug' => 'wohnung', 'nutzungsart' => 'wohnen', 'vermarktungsart' => 'kauf', 'data' => []]);

        $this->get('/makler/immobiliensuche')
            ->assertOk()
            ->assertSee('wire:click="resetFilters"', false)
            ->assertSee('<button type="button" class="!text-xs', false)
            ->assertSee('aria-sort="none"', false);
    }

    public function test_footer_offers_to_reopen_the_cookie_settings(): void
    {
        $this->get('/team')
            ->assertSee('class="cookiereset md:px-4"', false)
            ->assertSee('action="'.route('cookieconsent.reset').'"', false)
            ->assertSee(__('footer.cookie_settings'));
    }

    public function test_pages_hand_the_carousel_button_labels_to_the_script(): void
    {
        $this->get('/team')
            ->assertSee('data-carousel-pause="'.__('carousel.pause').'"', false)
            ->assertSee('data-carousel-play="'.__('carousel.play').'"', false);
    }

    public function test_service_tabs_other_than_the_default_get_their_own_title_and_description(): void
    {
        foreach ([['sepa-mandat', 1, 'SEPA-Mandat', 'Einzugsermächtigung für die Miete.'], ['meldezettel', 2, 'Meldezettel', 'Anmeldung innerhalb von 3 Tagen.']] as [$slug, $order, $name, $text]) {
            Service::withoutEvents(fn () => Service::create([
                'slug' => $slug, 'order' => $order, 'company' => 'Hausverwaltung',
                'name' => ['de' => $name, 'en' => ''], 'description' => ['de' => '<p>'.$text.'</p>', 'en' => ''],
                'links' => [], 'list' => [],
            ]));
        }

        $tab = $this->get('/service?tab=meldezettel')->getContent();
        $this->assertStringContainsString('<title>Meldezettel – be real Verwaltung</title>', $tab);
        $this->assertStringContainsString('content="Anmeldung innerhalb von 3 Tagen."', $tab);

        $default = $this->get('/service')->getContent();
        $this->assertStringNotContainsString('<title>SEPA-Mandat', $default);
    }
}
