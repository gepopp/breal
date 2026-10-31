<?php

namespace Tests\Feature;

use App\Enums\CompaniesEnum;
use App\Livewire\ContactForm;
use App\Models\Realty;
use App\Services\PageMeta;
use Database\Factories\QuestionFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SeoBatchAuditTest extends TestCase
{
    use RefreshDatabase;

    private function realty(array $attributes): Realty
    {
        return (new Realty)->forceFill($attributes + ['data' => []]);
    }

    public function test_long_unit_titles_keep_their_top_number_instead_of_being_truncated_away(): void
    {
        $title = 'HARDTMUTHGASSE - VOLL AUSGESTATTETE, GEPFLEGTE 2 ZIMMER-WOHNUNG - TOP ';

        $first = PageMeta::realtyTitle($this->realty(['title' => $title.'19']));
        $second = PageMeta::realtyTitle($this->realty(['title' => $title.'20']));

        $this->assertNotSame($first, $second);
        $this->assertStringEndsWith('Top 19', $first);
        $this->assertStringEndsWith('Top 20', $second);
        $this->assertLessThanOrEqual(PageMeta::TITLE_LIMIT, mb_strlen($first));
    }

    public function test_units_sharing_a_title_are_told_apart_by_their_object_number(): void
    {
        $first = PageMeta::realtyTitle($this->realty(['title' => 'Quartier Brennerei', 'objektnr_extern' => '6274/361']));
        $second = PageMeta::realtyTitle($this->realty(['title' => 'Quartier Brennerei', 'objektnr_extern' => '6274/360']));

        $this->assertStringStartsWith('Quartier Brennerei – Nr. 6274/361', $first);
        $this->assertNotSame($first, $second);
    }

    public function test_a_title_without_unit_identifier_is_unchanged(): void
    {
        $this->assertSame(PageMeta::compose('Doppelhaushälfte in Wien'), PageMeta::realtyTitle($this->realty(['title' => 'Doppelhaushälfte in Wien'])));
    }

    public function test_unit_descriptions_start_with_the_units_own_facts(): void
    {
        app()->setLocale('de');
        $text = '<p>Teil des Quartier Brennerei: '.str_repeat('Lageplan und Pläne. ', 20).'</p>';

        $first = PageMeta::realtyDescription($this->realty(['title' => 'Quartier Brennerei', 'zimmer' => 4, 'ort' => 'Markgrafneusiedl', 'objektnr_extern' => '6274/361']), $text);
        $second = PageMeta::realtyDescription($this->realty(['title' => 'Quartier Brennerei', 'zimmer' => 4, 'ort' => 'Markgrafneusiedl', 'objektnr_extern' => '6274/360']), $text);

        $this->assertStringStartsWith('4 Zimmer · Markgrafneusiedl · Nr. 6274/361 – Teil des Quartier Brennerei', $first);
        $this->assertNotSame($first, $second);
        $this->assertLessThanOrEqual(PageMeta::DESCRIPTION_LIMIT, mb_strlen($first));
    }

    public function test_property_page_serves_the_unit_specific_title(): void
    {
        Realty::create(['title' => 'Quartier Brennerei', 'slug' => 'quartier-brennerei', 'objektnr_extern' => '6274/361', 'data' => ['flaechen' => [], 'preise' => [], 'ausstattung' => [], 'zustand_angaben' => [], 'geo' => [], 'freitexte' => [], 'verwaltung_techn' => [], 'verwaltung_objekt' => [], 'objektkategorie' => [], 'anhaenge' => ['anhang' => []], 'kontaktperson' => ['vorname' => 'Tanja', 'name' => 'Test', 'email_direkt' => 'vertrieb@bereal-makler.at']]]);

        $this->get('/makler/immobilie/quartier-brennerei')
            ->assertOk()
            ->assertSee('<title>Quartier Brennerei – Nr. 6274/361 – be real Makler</title>', false);
    }

    public function test_faq_contact_form_prefills_the_subject_with_the_question(): void
    {
        $question = QuestionFactory::new()->create(['company' => CompaniesEnum::Hausverwaltung, 'question' => 'Wie finde ich die passende Hausverwaltung?']);

        Livewire::test(ContactForm::class, ['subject' => $question->question])
            ->assertSet('data.subject', 'Wie finde ich die passende Hausverwaltung?');

        $this->get('/faq/'.$question->slug)->assertOk()->assertSeeLivewire(ContactForm::class);
    }

    public function test_contact_form_subject_stays_empty_elsewhere(): void
    {
        Livewire::test(ContactForm::class)->assertSet('data.subject', '');
    }

    public function test_property_search_lists_detail_links_in_the_server_html(): void
    {
        Realty::create(['title' => 'Wohnung Suchseite', 'slug' => 'wohnung-suchseite', 'nutzungsart' => 'WOHNEN', 'vermarktungsart' => 'miete', 'data' => ['anhaenge' => ['anhang' => []]]]);

        $this->get('/makler/immobiliensuche')
            ->assertOk()
            ->assertSee('href="'.route('makler.immobilie', 'wohnung-suchseite').'"', false);
    }
}
