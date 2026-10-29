<?php

namespace Tests\Feature;

use App\Enums\CompaniesEnum;
use App\Filament\Pages\ContactSettings as ContactSettingsPage;
use App\Filament\Pages\PagesSettings as PagesSettingsPage;
use App\Mail\ContactRequestConfirmedMail;
use App\Mail\ContactRequestSolvedMail;
use App\Mail\VerificationEmail;
use App\Models\ContactRequest;
use App\Models\User;
use App\Services\CompanyInfo;
use App\Services\HtmlLinks;
use App\Settings\VacanciesSettings;
use Database\Factories\ContactpersonFactory;
use Database\Factories\DepartmentFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CompanyContactDataTest extends TestCase
{
    use RefreshDatabase;

    private const ADDRESS = 'Franz-Josefs-Kai 65/Mezzanin, 1010 Wien';

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function telNumbers(): array
    {
        return [
            'spaces' => ['+43 1 535 36 19', 'tel:+4315353619'],
            'double zero prefix' => ['0043 1 535 36 19', 'tel:+4315353619'],
            'brackets and dashes' => ['+43 (1) 535-36-19', 'tel:+4315353619'],
            'surrounding whitespace' => [' +43 664 123 45 67 ', 'tel:+436641234567'],
            'national number' => ['0664 1234567', 'tel:06641234567'],
            'already clean' => ['+4315353619', 'tel:+4315353619'],
        ];
    }

    #[DataProvider('telNumbers')]
    public function test_tel_links_have_no_spaces_or_separators(string $number, string $expected): void
    {
        $this->assertSame($expected, CompanyInfo::telUri($number));
    }

    public function test_company_data_follows_the_briefing(): void
    {
        $this->assertSame(self::ADDRESS, CompanyInfo::address());
        $this->assertSame('+43 1 535 36 19', CompanyInfo::phone());
        $this->assertSame('tel:+4315353619', CompanyInfo::phoneUri());
        $this->assertSame('mailto:office@bereal-makler.at', CompanyInfo::mailtoUri(' office@bereal-makler.at '));

        $this->assertSame('be real Immobilienmanagement GmbH', CompanyInfo::section('Hausverwaltung')['legal_name']);
        $this->assertSame('be real Immobilienmakler GmbH', CompanyInfo::section('Makler')['legal_name']);
        $this->assertSame('be real Baumanagement GmbH', CompanyInfo::section('Technik')['legal_name']);
        $this->assertSame('office@bereal-technik.at', CompanyInfo::section('Technik')['email']);
        $this->assertSame(['Lukas Eybel', 'Tanja Bachschwöller-Eybel'], CompanyInfo::section('Makler')['managing_directors']);
    }

    public function test_copyright_years_start_with_the_founding_year(): void
    {
        $this->travelTo(now()->setYear(2026));

        $this->assertSame('2006–2026', CompanyInfo::copyrightYears('Hausverwaltung'));
        $this->assertSame('2021–2026', CompanyInfo::copyrightYears('Makler'));
        $this->assertSame('2026', CompanyInfo::copyrightYears('Technik'));
    }

    public function test_opening_and_phone_hours_are_localized(): void
    {
        $this->assertSame(['Mo–Do' => '08:00–16:00', 'Fr' => '08:00–12:00'], CompanyInfo::hours('opening'));
        $this->assertSame(['Mo–Do' => '09:00–12:00 und 13:00–16:00', 'Fr' => '09:00–12:00'], CompanyInfo::hours('phone'));

        app()->setLocale('en');

        $this->assertSame(['Mon–Thu' => '09:00–12:00 and 13:00–16:00', 'Fri' => '09:00–12:00'], CompanyInfo::hours('phone'));
    }

    public function test_html_links_repairs_wrong_links_and_the_address(): void
    {
        $this->assertSame('<a href="mailto:a@b.at">a@b.at</a>', HtmlLinks::normalize('<a href="mail:a@b.at">a@b.at</a>'));
        $this->assertSame('<a href="mailto:a@b.at">x</a>', HtmlLinks::normalize('<a href="mailto:%20a@b.at">x</a>'));
        $this->assertSame('<a href="mailto:a@b.at">x</a>', HtmlLinks::normalize('<a href="mailto: a@b.at">x</a>'));
        $this->assertSame('<a href="mailto:a@b.at">x</a>', HtmlLinks::normalize('<a href="mailto:&nbsp;a@b.at">x</a>'));
        $this->assertSame('<a href="tel:+4315353619">+43 1 535 36 19</a>', HtmlLinks::normalize('<a href="tel:+43 1 535 36 19">+43 1 535 36 19</a>'));
        $this->assertSame('<a href="tel:+4315353619">x</a>', HtmlLinks::normalize('<a href="tel:%2B43%201%20535%2036%2019">x</a>'));

        foreach (['Franz Josefs Kai 65, 1010 Wien', 'Franz-Josef-Kai 65, 1010 Wien', 'Franz Josefs-Kai 65, 1010 Wien', 'Franz-Josefs-Kai 65, 1010 Wien'] as $variant) {
            $this->assertSame(self::ADDRESS, HtmlLinks::normalize($variant), $variant);
        }

        $this->assertSame('Franz-Josefs-Kai 12 und mehr', HtmlLinks::normalize('Franz Josefs Kai 12 und mehr'));
        $this->assertSame('Franz-Josefs-Kai 65/Mezzanin', HtmlLinks::normalize('Franz-Josefs-Kai 65/Mezzanin'));
        $this->assertSame('<p>Kein Link hier.</p>', HtmlLinks::normalize('<p>Kein Link hier.</p>'));
    }

    public function test_admin_texts_with_wrong_stored_links_are_repaired_on_the_page(): void
    {
        $settings = app(VacanciesSettings::class);
        $settings->vacancies_introtext_de = '<p>Bewerbung an <a href="mailto:%20office@bereal-immobilien.at">office@bereal-immobilien.at</a> oder <a href="tel:+43 1 535 36 19">anrufen</a>.</p>';
        $settings->save();

        $response = $this->get('/karriere');

        $response->assertSee('href="mailto:office@bereal-immobilien.at"', false);
        $response->assertSee('href="tel:+4315353619"', false);
        $response->assertDontSee('mailto:%20', false);
    }

    /**
     * @return array<string, array{0: string, 1: string, 2: string}>
     */
    public static function sectionPages(): array
    {
        return [
            'verwaltung' => ['/team', 'be real Immobilienmanagement GmbH', 'office@bereal-immobilien.at'],
            'makler' => ['/makler/team', 'be real Immobilienmakler GmbH', 'office@bereal-makler.at'],
            'technik' => ['/technik/team', 'be real Baumanagement GmbH', 'office@bereal-technik.at'],
        ];
    }

    #[DataProvider('sectionPages')]
    public function test_footer_shows_the_company_of_the_section_with_working_links(string $uri, string $legalName, string $email): void
    {
        $footer = $this->footerOf($this->get($uri)->getContent());

        $company = ['be real Immobilienmanagement GmbH' => 'Hausverwaltung', 'be real Immobilienmakler GmbH' => 'Makler', 'be real Baumanagement GmbH' => 'Technik'][$legalName];

        $this->assertStringContainsString('© '.CompanyInfo::copyrightYears($company).' '.$legalName, $footer);
        $this->assertStringContainsString('href="mailto:'.$email.'"', $footer);
        $this->assertStringContainsString('href="tel:+4315353619"', $footer);
        $this->assertStringContainsString('Franz-Josefs-Kai 65/Mezzanin', $footer);
        $this->assertStringContainsString('1010 Wien', $footer);
        $this->assertStringContainsString('Öffnungszeiten', $footer);
        $this->assertStringContainsString('Mo–Do: 08:00–16:00', $footer);
        $this->assertStringContainsString('Telefonzeiten', $footer);
        $this->assertStringContainsString('Mo–Do: 09:00–12:00 und 13:00–16:00', $footer);
        $this->assertStringContainsString('Fr: 09:00–12:00', $footer);

        foreach (['be real Immobilienmanagement GmbH', 'be real Immobilienmakler GmbH', 'be real Baumanagement GmbH'] as $other) {
            if ($other !== $legalName) {
                $this->assertStringNotContainsString($other, $footer);
            }
        }
    }

    public function test_english_footer_labels_the_hours(): void
    {
        $footer = $this->footerOf($this->get('/en/makler/team')->getContent());

        $this->assertStringContainsString('Phone hours', $footer);
        $this->assertStringContainsString('Mon–Thu: 09:00–12:00 and 13:00–16:00', $footer);
        $this->assertStringContainsString('Opening hours', $footer);
    }

    #[DataProvider('sectionPages')]
    public function test_contact_area_shows_the_section_email_as_link_and_the_full_address(string $teamUri, string $legalName, string $email): void
    {
        $uri = str_replace('/team', '/kontakt', $teamUri);

        $response = $this->get($uri);

        $response->assertSee('href="mailto:'.$email.'"', false);
        $response->assertSee('href="tel:+4315353619"', false);
        $response->assertSee(self::ADDRESS);
        $response->assertDontSee('Franz Josefs Kai');

        $sidebarLinks = preg_match_all('#href="mailto:([^"]+)"[^>]*class="underline#', $response->getContent(), $matches);
        $this->assertSame(1, $sidebarLinks);
        $this->assertSame([$email], $matches[1]);
    }

    public function test_contact_person_phone_links_have_no_spaces(): void
    {
        $department = DepartmentFactory::new()->create(['name' => 'Verwaltung', 'company' => CompaniesEnum::Hausverwaltung]);
        ContactpersonFactory::new()->create(['name' => 'Anna Beispiel', 'phone' => '+43 664 123 45 67', 'department_id' => $department->id]);

        $response = $this->get('/team');

        $response->assertSee('href="tel:+436641234567"', false);
        $response->assertDontSee('tel:+43 664', false);
    }

    public function test_no_page_links_with_a_wrong_mail_or_tel_scheme(): void
    {
        $paths = ['/', '/leistungen', '/service', '/karriere', '/kontakt', '/team', '/faq', '/unternehmensservice',
            '/makler/start', '/makler/kontakt', '/makler/team', '/technik/start', '/technik/kontakt', '/technik/team',
            '/impressum', '/barrierefreiheit', '/datenschutz', '/en', '/en/makler/kontakt', '/en/technik/kontakt'];

        foreach ($paths as $path) {
            $html = $this->get($path)->getContent();

            $this->assertSame(0, preg_match('#href="(mail:|mailto:(%20|\s)|tel:[^"]*(\s|%20|-|\())#i', $html), "Wrong contact link on {$path}.");
        }
    }

    public function test_e_mails_show_the_full_address(): void
    {
        $request = ContactRequest::create([
            'subject' => 'Anfrage', 'firstname' => 'Max', 'lastname' => 'Mustermann', 'email' => 'max@example.org',
            'phone' => '+43 1 234', 'message' => 'Hallo', 'company' => '', 'token' => 'abc',
        ]);

        foreach ([new VerificationEmail($request), new ContactRequestConfirmedMail($request), new ContactRequestSolvedMail($request)] as $mail) {
            $html = $mail->render();

            $this->assertStringContainsString(self::ADDRESS, $html, $mail::class);
            $this->assertStringNotContainsString('Franz Josefs Kai', $html);
        }
    }

    public function test_admin_pages_point_to_the_central_company_data_instead_of_dead_fields(): void
    {
        $this->actingAs(User::factory()->create(['admin' => true]));

        foreach ([ContactSettingsPage::class, PagesSettingsPage::class] as $page) {
            Livewire::test($page)
                ->assertSuccessful()
                ->assertSee('config/company.php')
                ->assertDontSee('contactform_phone');
        }
    }

    private function footerOf(string $html): string
    {
        $this->assertSame(1, preg_match('#<footer.*?</footer>#s', $html, $match));

        return html_entity_decode(strip_tags(str_replace(['<br>', '</p>'], ["\n", "\n"], $match[0]), '<a>'));
    }
}
