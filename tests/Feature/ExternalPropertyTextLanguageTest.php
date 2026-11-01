<?php

namespace Tests\Feature;

use App\Models\Realty;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExternalPropertyTextLanguageTest extends TestCase
{
    use RefreshDatabase;

    private function realty(): void
    {
        Realty::create([
            'title' => 'Helle Wohnung am Park',
            'slug' => 'helle-wohnung',
            'objektnr_extern' => '1/1',
            'beschreibung' => '<p>Wunderschöne Wohnung mit Balkon.</p>',
            'nutzungsart' => 'WOHNEN',
            'vermarktungsart' => 'miete',
            'data' => ['flaechen' => [], 'preise' => [], 'ausstattung' => [], 'zustand_angaben' => [], 'geo' => [], 'freitexte' => ['lage' => '<p>Ruhige Lage.</p>'], 'verwaltung_techn' => [], 'verwaltung_objekt' => [], 'objektkategorie' => [], 'anhaenge' => ['anhang' => []], 'kontaktperson' => ['vorname' => 'Tanja', 'name' => 'Test', 'email_direkt' => 'vertrieb@bereal-makler.at']],
        ]);
    }

    public function test_german_feed_texts_are_marked_as_german_on_english_pages(): void
    {
        $this->realty();

        $html = $this->get('/en/makler/immobilie/helle-wohnung')->assertOk()->getContent();

        $this->assertMatchesRegularExpression('/<h1[^>]*lang="de"[^>]*>\s*Helle Wohnung am Park/', $html);
        $this->assertMatchesRegularExpression('/<div\s+lang="de"\s*>\s*<p>[^<]*Balkon\.<\/p>/', $html);
        $this->assertMatchesRegularExpression('/<div\s+lang="de"\s*>\s*<p>Ruhige Lage\.<\/p>/', $html);

        $list = $this->get('/en/makler/immobiliensuche')->assertOk()->getContent();
        $this->assertMatchesRegularExpression('/<a [^>]*lang="de"[^>]*>\s*Helle Wohnung am Park/', $list);
    }

    public function test_german_pages_need_no_language_marker_on_feed_texts(): void
    {
        $this->realty();

        $html = $this->get('/makler/immobilie/helle-wohnung')->assertOk()->getContent();

        $this->assertDoesNotMatchRegularExpression('/<h1[^>]*lang="de"/', $html);
        $this->assertDoesNotMatchRegularExpression('/<div\s+lang="de"\s*>/', $html);
    }

    public function test_german_detail_values_are_marked_and_site_labels_stay_in_the_page_language(): void
    {
        Realty::create([
            'title' => 'Wohnung mit Details', 'slug' => 'wohnung-details', 'objektnr_extern' => '9/9',
            'beschreibung' => '<p>Text</p>', 'nutzungsart' => 'WOHNEN', 'vermarktungsart' => 'kauf',
            'data' => [
                'flaechen' => [], 'geo' => [], 'verwaltung_techn' => [], 'verwaltung_objekt' => [], 'objektkategorie' => [], 'freitexte' => [],
                'preise' => ['kaufpreisbrutto' => '100000', 'aussen_courtage' => '3% des Kaufpreises zzgl. 20% USt.'],
                'zustand_angaben' => ['baujahr' => '1990'],
                'ausstattung' => ['boden' => ['@attributes' => ['FLIESEN' => 1, 'PARKETT' => 0]], 'bad' => ['@attributes' => ['DUSCHE' => 1]]],
                'anhaenge' => ['anhang' => []], 'kontaktperson' => ['vorname' => 'Tanja', 'name' => 'Test', 'email_direkt' => 'vertrieb@bereal-makler.at'],
            ],
        ]);

        $english = $this->get('/en/makler/immobilie/wohnung-details')->assertOk()->getContent();

        $this->assertMatchesRegularExpression('/<span\s+lang="de"\s*>3% des Kaufpreises zzgl\. 20% USt\.<\/span>/', $english);
        $this->assertMatchesRegularExpression('/<span\s+lang="de"\s*>Fliesen<\/span>/', $english);
        $this->assertMatchesRegularExpression('/<span\s+lang="de"\s*>Dusche<\/span>/', $english);
        $this->assertStringContainsString('Property number: 9/9', $english);
        $this->assertStringContainsString('Residential', $english);
        $this->assertStringNotContainsString('Objektnummer', $english);

        $german = $this->get('/makler/immobilie/wohnung-details')->assertOk()->getContent();

        $this->assertStringContainsString('Objektnummer: 9/9', $german);
        $this->assertDoesNotMatchRegularExpression('/<span\s+lang="de"/', $german);
    }
}
