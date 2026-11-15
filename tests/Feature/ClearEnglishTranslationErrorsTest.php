<?php

namespace Tests\Feature;

use Database\Factories\CompetenceFactory;
use Database\Factories\ContactpersonFactory;
use Database\Factories\DepartmentFactory;
use Database\Factories\JobVacancyFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ClearEnglishTranslationErrorsTest extends TestCase
{
    use RefreshDatabase;

    private function runMigration(): void
    {
        (require database_path('migrations/2026_11_15_110000_correct_clear_english_translation_errors.php'))->up();
    }

    public function test_commercial_property_description_no_longer_holds_the_funding_text(): void
    {
        $competence = CompetenceFactory::new()->create([
            'name' => ['de' => 'Gewerbeimmobilienverwaltung', 'en' => 'Commercial property management'],
            'description' => ['de' => 'Echte Leidenschaft', 'en' => 'The public sector offers numerous funding programs for renovations.'],
        ]);

        $this->runMigration();

        $this->assertStringContainsString('commercial property', $competence->fresh()->getTranslation('description', 'en'));
        $this->assertSame('Echte Leidenschaft', $competence->fresh()->getTranslation('description', 'de'));
    }

    public function test_edited_description_is_left_alone(): void
    {
        $competence = CompetenceFactory::new()->create([
            'name' => ['de' => 'Gewerbeimmobilienverwaltung', 'en' => 'Commercial property management'],
            'description' => ['de' => 'Text', 'en' => 'Edited in the admin.'],
        ]);

        $this->runMigration();

        $this->assertSame('Edited in the admin.', $competence->fresh()->getTranslation('description', 'en'));
    }

    public function test_energy_certificate_gets_a_german_meta_description_only_when_empty(): void
    {
        $empty = CompetenceFactory::new()->create(['name' => ['de' => 'Energieausweis', 'en' => 'Energy performance certificate']]);

        $this->runMigration();

        $this->assertStringStartsWith('Energieausweise von be real Technik', $empty->fresh()->getTranslation('meta_description', 'de'));

        $empty->setTranslation('meta_description', 'de', 'Eigener Text');
        $empty->saveQuietly();
        $this->runMigration();

        $this->assertSame('Eigener Text', $empty->fresh()->getTranslation('meta_description', 'de'));
    }

    public function test_team_positions_are_corrected(): void
    {
        $department = DepartmentFactory::new()->create();
        $assistant = ContactpersonFactory::new()->create(['department_id' => $department->id, 'name' => 'Talin Shikouian', 'position' => ['de' => 'Assistenz Kreditoren', 'en' => 'Accounts Payable Team Manager']]);
        $apprentice = ContactpersonFactory::new()->create(['department_id' => $department->id, 'name' => 'Leander Krenn', 'position' => ['de' => 'Lehrling', 'en' => 'Reception Assistant']]);

        $this->runMigration();

        $this->assertSame('Accounts Payable Assistant', $assistant->fresh()->getTranslation('position', 'en'));
        $this->assertSame('Apprentice', $apprentice->fresh()->getTranslation('position', 'en'));
    }

    public function test_job_title_placeholders_are_replaced(): void
    {
        $vacancy = JobVacancyFactory::new()->create([
            'title' => ['de' => 'Teamleitung Stammdaten Objektbuchhaltung (m/w/d)', 'en' => 'Team Leader'],
            'job_title' => ['de' => 'accountant', 'en' => 'accountant'],
        ]);

        $this->runMigration();

        $this->assertSame('Teamleitung Stammdaten Objektbuchhaltung', $vacancy->fresh()->getTranslation('job_title', 'de'));
        $this->assertSame('Team Leader Master Data Property Accounting', $vacancy->fresh()->getTranslation('job_title', 'en'));
    }

    public function test_services_subheader_reads_services(): void
    {
        DB::table('settings')->where('name', 'technik_leistungen_subheader_en')->update(['payload' => json_encode('Our Service')]);
        DB::table('settings')->where('name', 'immobilien_leistungen_subheader_en')->update(['payload' => json_encode('My own text')]);

        $this->runMigration();

        $this->assertSame('Our Services', json_decode(DB::table('settings')->where('name', 'technik_leistungen_subheader_en')->value('payload')));
        $this->assertSame('My own text', json_decode(DB::table('settings')->where('name', 'immobilien_leistungen_subheader_en')->value('payload')));
    }

    public function test_map_notice_and_navigation_label_are_translated(): void
    {
        app()->setLocale('en');
        $this->assertSame('services', __('navigation.services'));
        $this->assertStringContainsString('optional cookies', __('map.cookie_notice'));

        app()->setLocale('de');
        $this->assertStringContainsString('optionale Cookies', __('map.cookie_notice'));
    }
}
