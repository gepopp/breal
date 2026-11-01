<?php

namespace Tests\Feature;

use App\Enums\CompaniesEnum;
use App\Models\JobVacancy;
use App\Models\Service;
use Database\Factories\JobVacancyFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AuditRoundThreeTest extends TestCase
{
    use RefreshDatabase;

    public function test_contract_type_is_translated_for_english_pages(): void
    {
        $vacancy = new JobVacancy(['contract_type' => 'Vollzeit']);

        app()->setLocale('de');
        $this->assertSame('Vollzeit', $vacancy->contractTypeLabel());

        app()->setLocale('en');
        $this->assertSame('Full-time', $vacancy->contractTypeLabel());
        $this->assertSame('Part-time', (new JobVacancy(['contract_type' => 'Teilzeit']))->contractTypeLabel());
        $this->assertSame('Sonderform', (new JobVacancy(['contract_type' => 'Sonderform']))->contractTypeLabel());
        $this->assertNull((new JobVacancy(['contract_type' => null]))->contractTypeLabel());
    }

    public function test_job_detail_page_shows_the_translated_contract_type(): void
    {
        JobVacancyFactory::new()->create([
            'company' => CompaniesEnum::Hausverwaltung, 'title' => 'Teamleitung', 'slug' => 'teamleitung',
            'contract_type' => 'Vollzeit', 'from' => now()->subDay(), 'to' => now()->addMonth(),
        ]);

        $this->get('/en/stellenanzeige/teamleitung')->assertOk()->assertSee('Full-time');
    }

    private function serviceWithGermanDocument(): void
    {
        Service::withoutEvents(fn () => Service::create(['slug' => 'sepa-mandat', 'order' => 1, 'company' => 'Hausverwaltung', 'name' => ['de' => 'SEPA-Mandat', 'en' => 'SEPA mandate'], 'description' => ['de' => '<p>Text</p>', 'en' => '<p>Text</p>'], 'links' => [], 'list' => []]));
        Service::withoutEvents(fn () => Service::create([
            'slug' => 'meldezettel', 'order' => 2, 'company' => 'Hausverwaltung',
            'name' => ['de' => 'Meldezettel', 'en' => 'Registration form'], 'description' => ['de' => '<p>Text</p>', 'en' => '<p>Text</p>'], 'list' => [],
            'links' => [['lang' => 'de', 'name' => 'Meldezettel Formular', 'path' => 'x.pdf', 'url' => 'https://files.example.test/meldezettel.pdf']],
        ]));
    }

    public function test_english_service_page_offers_the_german_document_when_there_is_no_english_one(): void
    {
        $this->serviceWithGermanDocument();

        $this->get('/en/service?tab=meldezettel')
            ->assertSee('https://files.example.test/meldezettel.pdf', false)
            ->assertSee('Meldezettel Formular (German)');

        $this->get('/service?tab=meldezettel')
            ->assertSee('Meldezettel Formular', false)
            ->assertDontSee('Meldezettel Formular (Deutsch)');
    }

    public function test_an_english_document_wins_over_the_german_one(): void
    {
        $this->serviceWithGermanDocument();
        $service = Service::where('slug', 'meldezettel')->first();
        $service->links = [
            ['lang' => 'de', 'name' => 'Meldezettel Formular', 'path' => 'x.pdf', 'url' => 'https://files.example.test/de.pdf'],
            ['lang' => 'en', 'name' => 'Registration form', 'path' => 'y.pdf', 'url' => 'https://files.example.test/en.pdf'],
        ];
        $service->saveQuietly();

        $this->get('/en/service?tab=meldezettel')
            ->assertSee('https://files.example.test/en.pdf', false)
            ->assertDontSee('https://files.example.test/de.pdf', false);
    }

    public function test_language_switch_keeps_the_selected_service_tab(): void
    {
        $this->serviceWithGermanDocument();

        $this->get('/service?tab=meldezettel')
            ->assertSee('href="'.url('/en/service').'?tab=meldezettel" class="font-bold uppercase" data-locale-switch>ENGLISH</a>', false)
            ->assertSee('<link rel="alternate" hreflang="en" href="'.url('/en/service').'?tab=meldezettel">', false);

        $this->get('/en/service?tab=meldezettel')
            ->assertSee('href="'.url('/service').'?tab=meldezettel" class="font-bold uppercase" data-locale-switch>DEUTSCH</a>', false);
    }

    public function test_language_switch_ignores_odd_tab_values_and_other_pages(): void
    {
        $this->get('/service?tab='.urlencode('<script>'))->assertDontSee('?tab=<script>', false);

        $this->get('/kontakt?tab=meldezettel')->assertSee('href="'.url('/en/kontakt').'" class="font-bold uppercase" data-locale-switch>ENGLISH</a>', false);
    }

    public function test_component_texts_are_german_on_german_pages(): void
    {
        app()->setLocale('de');

        $this->assertSame('Keine Ergebnisse gefunden', __('No results found'));
        $this->assertSame('Fett', __('Bold'));
        $this->assertSame('Texteditor', __('Rich text editor'));
    }

    public function test_second_translation_run_translates_a_german_copy_that_differs_in_whitespace(): void
    {
        $job = JobVacancyFactory::new()->create(['id' => 23, 'company' => CompaniesEnum::Hausverwaltung]);
        $german = '<p><strong>Ihre zukünftigen Tätigkeiten:</strong></p><p>·         Leitung eines Teams</p>';
        DB::table('job_vacancies')->where('id', $job->id)->update([
            'title' => json_encode(['de' => 'Immobilienverwalter:in (m/w/d) WEG mit Teamleitung ', 'en' => 'Immobilienverwalter:in (m/w/d) WEG mit Teamleitung ']),
            'job_title' => json_encode(['de' => 'Immobilienverwalter:in (m/w/d) WEG mit Teamleitung ', 'en' => 'Immobilienverwalter:in (m/w/d) WEG mit Teamleitung ']),
            'description' => json_encode(['de' => $german, 'en' => 'Ihre zukünftigen Tätigkeiten:·         Leitung eines Teams'], JSON_UNESCAPED_UNICODE),
        ]);

        (require database_path('migrations/2026_11_01_120000_refill_english_job_translations.php'))->up();

        $description = json_decode(DB::table('job_vacancies')->where('id', 23)->value('description'), true);
        $this->assertStringContainsString('Your future responsibilities', $description['en']);
        $this->assertSame($german, $description['de']);
    }
}
