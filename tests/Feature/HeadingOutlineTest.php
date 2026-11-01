<?php

namespace Tests\Feature;

use App\Enums\CompaniesEnum;
use App\Models\Service;
use Database\Factories\CompetenceFactory;
use Database\Factories\ContactpersonFactory;
use Database\Factories\DepartmentFactory;
use Database\Factories\JobVacancyFactory;
use Database\Factories\TimelineFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HeadingOutlineTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<int, array{level: int, text: string}>
     */
    private function headings(string $uri): array
    {
        $html = preg_replace('#<(script|style)\b.*?</\1>#s', '', $this->get($uri)->assertOk()->getContent());
        preg_match_all('#<h([1-6])\b[^>]*>(.*?)</h\1>#s', $html, $matches, PREG_SET_ORDER);

        return array_map(fn (array $match): array => [
            'level' => (int) $match[1],
            'text' => trim(preg_replace('/\s+/u', ' ', strip_tags($match[2]))),
        ], $matches);
    }

    private function assertNoSkippedLevels(string $uri): void
    {
        $previous = 0;

        foreach ($this->headings($uri) as $heading) {
            $this->assertLessThanOrEqual($previous + 1, $heading['level'], $uri.': "'.$heading['text'].'" is an H'.$heading['level'].' after an H'.$previous);
            $previous = $heading['level'];
        }
    }

    public function test_team_page_has_departments_below_the_h1_and_people_below_the_departments(): void
    {
        $department = DepartmentFactory::new()->create(['name' => 'Verwaltung', 'company' => CompaniesEnum::Hausverwaltung]);
        ContactpersonFactory::new()->create(['name' => 'Anna Beispiel', 'department_id' => $department->id]);

        $levels = array_column($this->headings('/team'), 'level', 'text');

        $this->assertSame(1, $levels['Das Team von be real Immobilien'] ?? null);
        $this->assertSame(2, $levels['Verwaltung'] ?? null);
        $this->assertSame(3, $levels['Anna Beispiel'] ?? null);
        $this->assertNoSkippedLevels('/team');
    }

    public function test_career_cards_are_h2_under_the_h1(): void
    {
        JobVacancyFactory::new()->create(['company' => CompaniesEnum::Hausverwaltung, 'title' => 'Teamleitung Verwaltung', 'from' => now()->subDay(), 'to' => now()->addMonth()]);

        $levels = array_column($this->headings('/karriere'), 'level', 'text');

        $this->assertSame(2, $levels['Teamleitung Verwaltung'] ?? null);
        $this->assertNoSkippedLevels('/karriere');
    }

    public function test_service_overview_cards_are_h2_under_the_h1(): void
    {
        CompetenceFactory::new()->create(['company' => CompaniesEnum::Hausverwaltung, 'name' => 'Hausverwaltung Test', 'slug' => 'hausverwaltung-test']);

        $levels = array_column($this->headings('/leistungen'), 'level', 'text');

        $this->assertSame(2, $levels['Hausverwaltung Test'] ?? null);
        $this->assertNoSkippedLevels('/leistungen');
    }

    public function test_homepage_sections_are_h2_and_their_cards_h3(): void
    {
        TimelineFactory::new()->create(['title' => 'Gründung der Verwaltung']);

        $levels = array_column($this->headings('/'), 'level', 'text');

        $this->assertSame(3, $levels['Gründung der Verwaltung'] ?? null);
        $this->assertContains(2, $levels, 'sections need an H2');
        $this->assertNoSkippedLevels('/');
    }

    public function test_other_pages_do_not_skip_heading_levels(): void
    {
        foreach (['/service', '/kontakt', '/faq', '/impressum', '/makler', '/technik', '/technik/leistungen', '/en', '/unternehmensservice', '/makler/ueber-uns', '/makler/karriere', '/makler/team', '/makler/kontakt', '/technik/team', '/datenschutz', '/barrierefreiheit'] as $uri) {
            $this->assertNoSkippedLevels($uri);
        }
    }

    public function test_cookie_banner_title_is_not_a_heading(): void
    {
        $html = $this->get('/team')->getContent();

        $this->assertStringContainsString('aria-labelledby="cookies-title"', $html);
        $this->assertStringContainsString('<p id="cookies-title" class="cookies__title">', $html);
        $this->assertStringNotContainsString('<h2 class="cookies__title">', $html);
    }

    public function test_selected_service_gets_a_hidden_h2(): void
    {
        $this->get('/service')->assertOk();

        Service::withoutEvents(fn () => Service::create([
            'slug' => 'sepa-mandat', 'order' => 1, 'company' => 'Hausverwaltung',
            'name' => ['de' => 'SEPA-Mandat', 'en' => ''], 'description' => ['de' => '<p>Text</p>', 'en' => ''], 'links' => [], 'list' => [],
        ]));

        $this->get('/service')->assertSee('<h2 class="sr-only">SEPA-Mandat</h2>', false);
    }
}
