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
}
