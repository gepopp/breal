<?php

namespace Tests\Feature;

use App\Enums\CompaniesEnum;
use Database\Factories\JobVacancyFactory;
use Database\Factories\QuestionFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EnglishTranslationFillTest extends TestCase
{
    use RefreshDatabase;

    private function runMigration(): void
    {
        (require database_path('migrations/2026_10_31_240000_fill_missing_english_translations.php'))->up();
    }

    private function translation(string $table, int $id, string $field): array
    {
        return json_decode(DB::table($table)->where('id', $id)->value($field), true);
    }

    private function question(int $id, array $question, array $answer): void
    {
        QuestionFactory::new()->create(['id' => $id, 'company' => CompaniesEnum::Hausverwaltung]);
        DB::table('questions')->where('id', $id)->update([
            'question' => json_encode($question, JSON_UNESCAPED_UNICODE),
            'answer' => json_encode($answer, JSON_UNESCAPED_UNICODE),
        ]);
    }

    public function test_an_empty_english_faq_entry_is_filled(): void
    {
        $this->question(2, ['de' => 'Was zählt als Notfall?', 'en' => ''], ['de' => '<p>Ein Notfall liegt vor bei: Wasserrohrbruch</p>', 'en' => '']);

        $this->runMigration();

        $this->assertSame('What counts as an emergency?', $this->translation('questions', 2, 'question')['en']);
        $this->assertStringContainsString('Burst water pipe', $this->translation('questions', 2, 'answer')['en']);
        $this->assertSame('Was zählt als Notfall?', $this->translation('questions', 2, 'question')['de']);
    }

    public function test_an_english_text_edited_in_the_admin_is_kept(): void
    {
        $this->question(2, ['de' => 'Was zählt als Notfall?', 'en' => 'What is an emergency?'], ['de' => '<p>Ein Notfall liegt vor bei: Wasserrohrbruch</p>', 'en' => '<p>Own wording</p>']);

        $this->runMigration();

        $this->assertSame('What is an emergency?', $this->translation('questions', 2, 'question')['en']);
        $this->assertSame('<p>Own wording</p>', $this->translation('questions', 2, 'answer')['en']);
    }

    public function test_entries_other_than_the_listed_ones_are_not_touched(): void
    {
        $this->question(4, ['de' => 'Expedita similique odio atque qui.', 'en' => ''], ['de' => 'Quibusdam molestiae.', 'en' => '']);

        $this->runMigration();

        $this->assertSame('', $this->translation('questions', 4, 'question')['en']);
    }

    public function test_a_job_whose_english_text_is_a_copy_of_the_german_one_is_translated(): void
    {
        $job = JobVacancyFactory::new()->create(['id' => 22, 'company' => CompaniesEnum::Hausverwaltung]);
        $german = '<p><strong>Ihre zukünftigen Tätigkeiten:</strong></p><p>· Leitung eines Teams</p>';
        DB::table('job_vacancies')->where('id', $job->id)->update([
            'title' => json_encode(['de' => 'Immobilienverwalter:in (m/w/d) MRG mit Teamleitung', 'en' => 'Immobilienverwalter:in (m/w/d) MRG mit Teamleitung']),
            'job_title' => json_encode(['de' => 'Immobilienverwalter:in (m/w/d) MRG mit Teamleitung', 'en' => 'Immobilienverwalter:in (m/w/d) MRG mit Teamleitung']),
            'description' => json_encode(['de' => $german, 'en' => strip_tags($german)], JSON_UNESCAPED_UNICODE),
        ]);

        $this->runMigration();

        $this->assertSame('Property Manager (m/f/d) MRG with Team Leadership', $this->translation('job_vacancies', 22, 'title')['en']);
        $this->assertStringContainsString('Your future responsibilities', $this->translation('job_vacancies', 22, 'description')['en']);
        $this->assertSame($german, $this->translation('job_vacancies', 22, 'description')['de']);
    }

    public function test_missing_rows_are_skipped_and_the_migration_can_run_twice(): void
    {
        $this->runMigration();
        $this->runMigration();

        $this->assertSame(0, DB::table('questions')->count());
    }
}
