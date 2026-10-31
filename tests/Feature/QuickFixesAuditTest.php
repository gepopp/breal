<?php

namespace Tests\Feature;

use App\Models\JobVacancy;
use App\Services\HtmlLists;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class QuickFixesAuditTest extends TestCase
{
    use RefreshDatabase;

    public function test_salary_is_formatted_for_the_page_language(): void
    {
        $vacancy = new JobVacancy(['salary' => 63000, 'unit_text' => 'Jährlich']);

        app()->setLocale('de');
        $this->assertSame('63.000,00 € pro Jahr', str_replace("\u{a0}", ' ', $vacancy->formattedSalary()));

        app()->setLocale('en');
        $this->assertSame('€63,000.00 per year', $vacancy->formattedSalary());
    }

    public function test_salary_without_a_known_interval_shows_only_the_amount(): void
    {
        app()->setLocale('de');

        $this->assertSame('1.000,00 €', str_replace("\u{a0}", ' ', (new JobVacancy(['salary' => 1000, 'unit_text' => null]))->formattedSalary()));
        $this->assertNull((new JobVacancy(['salary' => null]))->formattedSalary());
    }

    public function test_imported_h1_is_demoted_to_h2(): void
    {
        $this->assertSame('<h2 class="x">Titel</h2><p>Text</p>', HtmlLists::demoteH1('<h1 class="x">Titel</h1><p>Text</p>'));
        $this->assertSame('<header>kept</header>', HtmlLists::demoteH1('<header>kept</header>'));
    }

    public function test_cookie_settings_text_is_grammatically_correct(): void
    {
        $text = trans('cookieConsent::cookies.categories.essentials.description', [], 'de');

        $this->assertStringContainsString('benötigen wir nicht Ihre Zustimmung', $text);
    }

    public function test_contact_link_in_the_top_bar_stays_in_the_current_section(): void
    {
        $this->get('/makler/start')->assertSee('href="'.route('makler.kontakt').'" class="font-bold uppercase"', false);
        $this->get('/technik/start')->assertSee('href="'.route('technik.kontakt').'" class="font-bold uppercase"', false);
        $this->get('/team')->assertSee('href="'.route('hausverwaltung.kontakt').'" class="font-bold uppercase"', false);
    }

    public function test_logo_on_shared_pages_links_to_the_group_start_not_to_makler(): void
    {
        $html = $this->get('/impressum')->assertOk()->getContent();
        $this->assertMatchesRegularExpression('#<a href="'.preg_quote(route('hausverwaltung.home'), '#').'">\s*<img src="[^"]*bereal\.svg" alt="be real"#', $html);
    }

    public function test_damage_form_link_migration_makes_the_stored_links_relative(): void
    {
        $this->storeContactText('contact_introtext_de', '<a href="https://bereal-immobilien.at/service?tab=schadensformular">Schadensformular</a>');
        $this->storeContactText('contact_introtext_en', '<a href="https://www.bereal-immobilien.at/service?tab=schadensformular">damage report form</a>');

        (require database_path('migrations/2026_10_31_230000_make_schadensformular_link_relative.php'))->up();

        $this->assertStringContainsString('href="/service?tab=schadensformular"', $this->storedContactText('contact_introtext_de'));
        $this->assertStringContainsString('href="/en/service?tab=schadensformular"', $this->storedContactText('contact_introtext_en'));
    }

    public function test_damage_form_link_migration_leaves_other_links_alone(): void
    {
        $this->storeContactText('contact_introtext_de', '<a href="https://example.test/other">Link</a>');

        (require database_path('migrations/2026_10_31_230000_make_schadensformular_link_relative.php'))->up();

        $this->assertSame('<a href="https://example.test/other">Link</a>', $this->storedContactText('contact_introtext_de'));
    }

    private function storeContactText(string $name, string $text): void
    {
        DB::table('settings')->updateOrInsert(
            ['group' => 'translatable_contact', 'name' => $name],
            ['locked' => false, 'payload' => json_encode($text)]
        );
    }

    private function storedContactText(string $name): string
    {
        return json_decode(DB::table('settings')->where('group', 'translatable_contact')->where('name', $name)->value('payload'));
    }
}
