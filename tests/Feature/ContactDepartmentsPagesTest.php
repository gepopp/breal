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
            'verwaltung team' => ['/team', 'Verwaltung Spezialteam', 'Makler Vermittlungsteam'],
            'verwaltung kontakt' => ['/kontakt', 'Verwaltung Spezialteam', 'Makler Vermittlungsteam'],
            'makler team' => ['/makler/team', 'Makler Vermittlungsteam', 'Verwaltung Spezialteam'],
            'makler kontakt' => ['/makler/kontakt', 'Makler Vermittlungsteam', 'Verwaltung Spezialteam'],
        ];
    }

    #[DataProvider('pagesWithOwnTeam')]
    public function test_pages_list_only_the_departments_of_their_own_company_in_the_server_html(string $uri, string $expected, string $unexpected): void
    {
        $response = $this->get($uri);

        $response->assertOk();
        $response->assertSee($expected);
        $response->assertDontSee($unexpected);
    }

    public function test_contact_persons_are_rendered_in_the_initial_html(): void
    {
        $this->get('/team')->assertSee('Anna Beispiel')->assertDontSee('Berta Muster');
        $this->get('/makler/team')->assertSee('Berta Muster')->assertDontSee('Anna Beispiel');
    }

    public function test_technik_pages_do_not_show_the_departments_of_other_companies(): void
    {
        $this->get('/technik/team')->assertOk()->assertDontSee('Anna Beispiel')->assertDontSee('Berta Muster');
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
