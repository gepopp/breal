<?php

namespace Tests\Feature;

use App\Enums\CompaniesEnum;
use Database\Factories\ContactpersonFactory;
use Database\Factories\DepartmentFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ContactDepartmentsPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createDepartmentWithPerson(CompaniesEnum::Hausverwaltung, 'Verwaltung Spezialteam', 'Anna Beispiel');
        $this->createDepartmentWithPerson(CompaniesEnum::Makler, 'Makler Vermittlungsteam', 'Berta Muster');
    }

    /**
     * @return array<string, array{0: string, 1: string, 2: string}>
     */
    public static function pagesWithOwnTeam(): array
    {
        return [
            'verwaltung kontakt' => ['/kontakt', 'Verwaltung Spezialteam', 'Makler Vermittlungsteam'],
            'makler kontakt' => ['/makler/kontakt', 'Makler Vermittlungsteam', 'Verwaltung Spezialteam'],
        ];
    }

    #[DataProvider('pagesWithOwnTeam')]
    public function test_contact_pages_list_only_the_departments_of_their_own_company_in_the_server_html(string $uri, string $expected, string $unexpected): void
    {
        $response = $this->get($uri);

        $response->assertOk();
        $response->assertSee($expected);
        $response->assertDontSee($unexpected);
    }

    public function test_contact_persons_are_rendered_in_the_initial_html(): void
    {
        $this->get('/kontakt')->assertSee('Anna Beispiel')->assertDontSee('Berta Muster');
        $this->get('/makler/kontakt')->assertSee('Berta Muster')->assertDontSee('Anna Beispiel');
    }

    #[DataProvider('teamPages')]
    public function test_every_team_page_shows_the_same_team(string $uri): void
    {
        $this->get($uri)
            ->assertOk()
            ->assertSee('Verwaltung Spezialteam')
            ->assertSee('Anna Beispiel')
            ->assertSee('Makler Vermittlungsteam')
            ->assertSee('Berta Muster')
            ->assertSee('rel="canonical" href="'.route('hausverwaltung.team').'"', false);
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function teamPages(): array
    {
        return [
            'verwaltung' => ['/team'],
            'makler' => ['/makler/team'],
            'technik' => ['/technik/team'],
        ];
    }

    public function test_technik_contact_page_does_not_show_the_departments_of_other_companies(): void
    {
        $this->get('/technik/kontakt')->assertOk()->assertDontSee('Verwaltung Spezialteam')->assertDontSee('Makler Vermittlungsteam');
    }

    public function test_contact_page_contains_the_form_in_the_initial_html(): void
    {
        $response = $this->get('/kontakt');

        $response->assertSee(__('contact.form.firstname'));
        $response->assertSee(__('contact.form.message'));
        $response->assertSee(__('form.required'));
    }

    private function createDepartmentWithPerson(CompaniesEnum $company, string $department, string $person): void
    {
        $departmentModel = DepartmentFactory::new()->create(['name' => $department, 'company' => $company]);

        ContactpersonFactory::new()->create(['name' => $person, 'department_id' => $departmentModel->id]);
    }
}
