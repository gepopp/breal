<?php

namespace Tests\Feature;

use App\Livewire\ContactForm;
use App\Livewire\Hausverwaltung\Service as ServiceComponent;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ServicePageTest extends TestCase
{
    use RefreshDatabase;

    private const FIRST_TEXT = 'Beschreibung der Lastschrift mit allen Einzelheiten.';

    private const SECOND_TEXT = 'Beschreibung des Meldezettels mit allen Einzelheiten.';

    protected function setUp(): void
    {
        parent::setUp();

        $this->createService('sepa-mandat', 1, 'SEPA-Mandat', 'SEPA direct debit', self::FIRST_TEXT, 'Direct debit description in English.');
        $this->createService('meldezettel', 2, 'Meldezettel', '', self::SECOND_TEXT, '', [
            'list' => [['icon' => 'stern', 'list' => ['Erster Schritt', 'Zweiter Schritt']]],
            'links' => [
                ['lang' => 'de', 'name' => 'Meldezettel Formular', 'path' => 'x.pdf', 'url' => 'https://files.example.test/meldezettel.pdf'],
                ['lang' => 'en', 'name' => 'Registration form', 'path' => 'y.pdf', 'url' => 'https://files.example.test/registration.pdf'],
            ],
        ]);
        $this->createService('schaden', 3, 'Schadensformular', 'Damage report', 'Beschreibung des Schadens.', 'Damage description.', ['form' => 'address']);
    }

    public function test_menu_and_default_service_are_part_of_the_server_html(): void
    {
        $response = $this->get('/service');

        $response->assertOk();

        foreach (['sepa-mandat', 'meldezettel', 'schaden'] as $slug) {
            $response->assertSee('href="'.$this->root().'/service?tab='.$slug.'"', false);
        }

        $response->assertSee('SEPA-Mandat');
        $response->assertSee('Meldezettel');
        $response->assertSee(self::FIRST_TEXT);
        $response->assertDontSee(self::SECOND_TEXT);
        $response->assertDontSee('x-for="service in services"', false);
        $response->assertDontSee('x-html', false);
    }

    public function test_selected_service_renders_description_list_and_downloads(): void
    {
        $response = $this->get('/service?tab=meldezettel');

        $response->assertOk();
        $response->assertSee(self::SECOND_TEXT);
        $response->assertDontSee(self::FIRST_TEXT);
        $response->assertSee('Erster Schritt');
        $response->assertSee('Zweiter Schritt');
        $response->assertSee('href="https://files.example.test/meldezettel.pdf"', false);
        $response->assertSee('Meldezettel Formular');
        $response->assertDontSee('registration.pdf');
    }

    public function test_default_and_unknown_tabs_show_the_first_service_with_the_plain_canonical(): void
    {
        foreach (['/service', '/service?tab=gibt-es-nicht'] as $uri) {
            $response = $this->get($uri);

            $response->assertSee(self::FIRST_TEXT);
            $response->assertSee('<link rel="canonical" href="'.$this->root().'/service">', false);
        }
    }

    public function test_other_services_declare_their_own_canonical_url(): void
    {
        $this->get('/service?tab=meldezettel')
            ->assertSee('<link rel="canonical" href="'.$this->root().'/service?tab=meldezettel">', false);
    }

    public function test_english_page_uses_english_texts_prefixed_links_and_german_fallback(): void
    {
        $english = $this->get('/en/service');

        $english->assertSee('SEPA direct debit');
        $english->assertSee('Direct debit description in English.');
        $english->assertSee('href="'.$this->root().'/en/service?tab=meldezettel"', false);

        $fallback = $this->get('/en/service?tab=meldezettel');

        $fallback->assertSee('Meldezettel');
        $fallback->assertSee(self::SECOND_TEXT);
        $fallback->assertSee('href="https://files.example.test/registration.pdf"', false);
        $fallback->assertDontSee('meldezettel.pdf');
    }

    public function test_contact_form_is_only_rendered_for_services_that_need_it(): void
    {
        $this->get('/service?tab=schaden')->assertSeeLivewire(ContactForm::class);
        $this->get('/service?tab=meldezettel')->assertDontSeeLivewire(ContactForm::class);
    }

    public function test_switching_the_tab_renders_the_other_service_on_the_server(): void
    {
        Livewire::test(ServiceComponent::class)
            ->assertSee(self::FIRST_TEXT)
            ->set('tab', 'meldezettel')
            ->assertSee(self::SECOND_TEXT)
            ->assertDontSee(self::FIRST_TEXT)
            ->assertSee('href="'.$this->root().'/service?tab=sepa-mandat"', false);
    }

    public function test_filter_receives_plain_service_names_it_can_search(): void
    {
        $german = $this->get('/service');

        $german->assertSee($this->filterItemName('SEPA-Mandat'), false);
        $german->assertDontSee($this->filterItemName('{'), false);

        $this->get('/kontakt')->assertSee($this->filterItemName('Meldezettel'), false);
        $this->get('/en/service')->assertSee($this->filterItemName('SEPA direct debit'), false);
    }

    private function filterItemName(string $name): string
    {
        $quote = chr(92).'u0022';

        return $quote.'name'.$quote.':'.($name === '{' ? '{' : $quote.$name.$quote);
    }

    public function test_navbar_dropdown_links_to_every_service_without_embedding_descriptions(): void
    {
        $response = $this->get('/kontakt');

        $response->assertSee('href="'.$this->root().'/service?tab=meldezettel"', false);
        $response->assertSee('Filtern Sie aus 3 Services');
        $response->assertDontSee(self::FIRST_TEXT);
        $response->assertDontSee(self::SECOND_TEXT);

        $this->get('/en/kontakt')->assertSee('Filter from 3 services');
    }

    /**
     * @param  array<string, mixed>  $extra
     */
    private function createService(string $slug, int $order, string $nameDe, string $nameEn, string $descriptionDe, string $descriptionEn, array $extra = []): void
    {
        Service::withoutEvents(fn () => Service::create(array_merge([
            'slug' => $slug,
            'order' => $order,
            'company' => 'Hausverwaltung',
            'name' => ['de' => $nameDe, 'en' => $nameEn],
            'description' => ['de' => $descriptionDe, 'en' => $descriptionEn],
            'links' => [],
            'list' => [],
        ], $extra)));
    }

    private function root(): string
    {
        return rtrim(config('app.url'), '/');
    }
}
