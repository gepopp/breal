<?php

namespace Tests\Feature;

use App\Models\Realty;
use App\Services\HtmlLists;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GalleryNoteAndSitemapTest extends TestCase
{
    use RefreshDatabase;

    private const PHOTO = 'https://storage.justimmo.at/thumb/2f0aa01cba6667ff198bcd4d0a911337/fm_h1080_w1920/mDsbwapgcqkPHQS2Sf789K.jpg';

    private function realty(string $description = '<p>Text</p>'): void
    {
        $attachment = fn (string $name, ?string $title): array => array_filter([
            '@attributes' => ['gruppe' => 'BILD'],
            'format' => 'jpg',
            'anhangtitel' => $title,
            'daten' => ['pfad' => str_replace('mDsbwapgcqkPHQS2Sf789K', $name, self::PHOTO)],
        ]);

        Realty::create([
            'title' => 'Helle Wohnung am Park', 'slug' => 'helle-wohnung', 'objektnr_extern' => '1/1',
            'beschreibung' => $description, 'nutzungsart' => 'WOHNEN', 'vermarktungsart' => 'miete',
            'data' => [
                'flaechen' => [], 'preise' => [], 'ausstattung' => [], 'zustand_angaben' => [], 'geo' => [], 'freitexte' => [],
                'verwaltung_techn' => [], 'verwaltung_objekt' => [], 'objektkategorie' => [],
                'anhaenge' => ['anhang' => [$attachment('one', 'Wohnzimmer mit zwei Fenstern'), $attachment('two', null), $attachment('three', '  ')]],
                'kontaktperson' => ['vorname' => 'Tanja', 'name' => 'Test', 'email_direkt' => 'vertrieb@bereal-makler.at'],
            ],
        ]);
    }

    public function test_gallery_images_get_individual_alt_texts_and_the_thumbnails_are_hidden_from_assistive_technology(): void
    {
        $this->realty();

        $html = $this->get('/makler/immobilie/helle-wohnung')->assertOk()->getContent();

        $this->assertStringContainsString('alt="Wohnzimmer mit zwei Fenstern"', $html);
        $this->assertStringContainsString('alt="Foto 2 von 3"', $html);
        $this->assertStringContainsString('alt="Foto 3 von 3"', $html);
        $this->assertStringNotContainsString('alt="Helle Wohnung am Park"', $html);
        $this->assertMatchesRegularExpression('#realty-gallery-thumbs"\s+aria-hidden="true"#', $html);
        $this->assertSame(3, preg_match_all('#<img [^>]*realty-image/160/[^>]*alt=""#s', $html));
    }

    public function test_gallery_styles_are_scoped_and_no_longer_inlined_in_the_page(): void
    {
        $this->realty();

        $html = $this->get('/makler/immobilie/helle-wohnung')->assertOk()->getContent();

        $this->assertStringNotContainsString('.mySwiper', $html);
        $this->assertStringNotContainsString('.swiper-slide img {', $html);
        $this->assertStringContainsString('class="realty-gallery my-8"', $html);

        $css = file_get_contents(resource_path('css/app.css'));
        $this->assertStringContainsString('.realty-gallery .swiper-slide img', $css);
        $this->assertDoesNotMatchRegularExpression('/^\.swiper(?:-slide)?(?:\s+img)?\s*\{/m', $css);
    }

    public function test_english_property_pages_say_that_the_texts_are_german(): void
    {
        $this->realty();

        $this->get('/en/makler/immobilie/helle-wohnung')->assertOk()
            ->assertSee('The texts for this property are only available in German.');

        $this->get('/makler/immobilie/helle-wohnung')->assertOk()
            ->assertDontSee('data-german-text-note', false)
            ->assertDontSee('only available in German');
    }

    public function test_sitemap_leaves_out_the_team_pages_that_share_the_team_canonical(): void
    {
        $xml = $this->get('sitemap.xml')->assertOk()->getContent();

        $this->assertStringContainsString('<loc>'.url('/team').'</loc>', $xml);
        $this->assertStringContainsString('<loc>'.url('/en/team').'</loc>', $xml);

        foreach (['/makler/team', '/technik/team', '/en/makler/team', '/en/technik/team'] as $path) {
            $this->assertStringNotContainsString('<loc>'.url($path).'</loc>', $xml, $path);
        }
    }

    public function test_calendly_links_with_a_bare_address_as_text_get_a_descriptive_label(): void
    {
        $html = '<p>Termin: <a href="https://calendly.com/bereal/besichtigung" target="_blank">https://calendly.com/bereal/besichtigung</a></p>';

        $this->assertSame(
            '<p>Termin: <a href="https://calendly.com/bereal/besichtigung" target="_blank">Besichtigungstermin buchen</a></p>',
            HtmlLists::labelBookingLinks($html, 'Besichtigungstermin buchen'),
        );
        $this->assertStringContainsString('>Besichtigungstermin buchen</a>', HtmlLists::labelBookingLinks('<a href="https://calendly.com/x">www.calendly.com/x</a>', 'Besichtigungstermin buchen'));
    }

    public function test_other_links_and_readable_calendly_texts_stay_untouched(): void
    {
        foreach ([
            '<a href="https://calendly.com/bereal">Jetzt Termin vereinbaren</a>',
            '<a href="https://example.com/">https://example.com/</a>',
            '<a href="https://calendly.com.evil.example/x">https://calendly.com.evil.example/x</a>',
        ] as $html) {
            $this->assertSame($html, HtmlLists::labelBookingLinks($html, 'Besichtigungstermin buchen'));
        }
    }

    public function test_property_page_labels_the_booking_link_in_the_description(): void
    {
        $this->realty('<p><a href="https://calendly.com/bereal/x">https://calendly.com/bereal/x</a></p>');

        $this->get('/makler/immobilie/helle-wohnung')->assertOk()
            ->assertSee('>Besichtigungstermin buchen</a>', false);

        $this->get('/en/makler/immobilie/helle-wohnung')->assertOk()
            ->assertSee('>Book a viewing</a>', false);
    }
}
