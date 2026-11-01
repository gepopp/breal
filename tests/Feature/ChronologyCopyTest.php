<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ChronologyCopyTest extends TestCase
{
    use RefreshDatabase;

    private function runMigration(): void
    {
        (require database_path('migrations/2026_11_01_160000_correct_chronology_wording.php'))->up();
    }

    private function entry(int $year, array $title, array $description): void
    {
        DB::table('timelines')->insert([
            'year' => $year,
            'title' => json_encode($title, JSON_UNESCAPED_UNICODE),
            'description' => json_encode($description, JSON_UNESCAPED_UNICODE),
        ]);
    }

    /**
     * @return array<string, string>
     */
    private function storedDescription(int $year): array
    {
        return json_decode(DB::table('timelines')->where('year', $year)->value('description'), true);
    }

    public function test_missing_space_and_first_person_are_corrected(): void
    {
        $this->entry(2021, ['de' => 'Maklerservice'], ['de' => 'Ob Verkauf, Vermietung oder individuelle Beratung.Unser Maklerservice richtet sich an alle.']);
        $this->entry(2009, ['de' => 'Einstieg', 'en' => 'Joins'], [
            'de' => 'Von Anfang an war es mir wichtig, alles kennenzulernen.',
            'en' => "From the very beginning, it was important to me to get to know everything \u{2013} a step on the path to my current responsibilities.",
        ]);

        $this->runMigration();

        $this->assertSame('Ob Verkauf, Vermietung oder individuelle Beratung – unser Maklerservice richtet sich an alle.', $this->storedDescription(2021)['de']);
        $this->assertSame('Von Anfang an war es ihm wichtig, alles kennenzulernen.', $this->storedDescription(2009)['de']);
        $this->assertStringContainsString('important to him to get', $this->storedDescription(2009)['en']);
        $this->assertStringContainsString('his current responsibilities', $this->storedDescription(2009)['en']);
        $this->assertStringNotContainsString(' me ', $this->storedDescription(2009)['en']);
    }

    public function test_company_name_and_invisible_characters_are_cleaned_in_the_english_text(): void
    {
        $this->entry(2006, ['de' => 'Gründung', 'en' => 'Founded'], [
            'de' => 'Neuer Name.',
            'en' => "With a new name \u{2013} Bontus Property Management \u{2013} \u{200B}\u{200B}and a clear focus. \n",
        ]);

        $this->runMigration();

        $this->assertSame('With a new name – Bontus Immobilienverwaltung – and a clear focus.', $this->storedDescription(2006)['en']);
    }

    public function test_the_migration_can_run_twice_and_leaves_other_texts_alone(): void
    {
        $this->entry(2015, ['de' => 'Übernahme'], ['de' => 'Mit mir hat das nichts zu tun, Dr. Stingl.']);
        $this->entry(2021, ['de' => 'Maklerservice'], ['de' => 'Beratung.Unser Service.']);

        $this->runMigration();
        $this->runMigration();

        $this->assertSame('Mit mir hat das nichts zu tun, Dr. Stingl.', $this->storedDescription(2015)['de']);
        $this->assertSame('Beratung. Unser Service.', $this->storedDescription(2021)['de']);
    }

    public function test_the_chronology_slider_can_be_operated_without_a_mouse(): void
    {
        $this->entry(2025, ['de' => 'Übernahme'], ['de' => 'Text']);

        $german = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('x-on:click="toggleAutoplay()"', $german);
        $this->assertStringContainsString('aria-label="'.__('carousel.previous').'"', $german);
        $this->assertStringContainsString('aria-label="'.__('carousel.next').'"', $german);
        $this->assertMatchesRegularExpression('/<button type="button" x-on:click="goto\(0\)"/', $german);
        $this->assertStringContainsString('keyboard: {', $german);
        $this->assertStringNotContainsString('console.log(Alpine.raw(this.slider))', $german);
        $this->assertStringContainsString('slideToLoop(index)', $german);
    }
}
