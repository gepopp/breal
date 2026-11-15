<?php

namespace Tests\Feature;

use App\Models\Timeline;
use Database\Factories\ContactpersonFactory;
use Database\Factories\DepartmentFactory;
use Database\Factories\TimelineFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UnifyEnglishWordingTest extends TestCase
{
    use RefreshDatabase;

    private function runMigration(): void
    {
        (require database_path('migrations/2026_11_15_130000_unify_english_wording.php'))->up();
    }

    public function test_clerk_positions_are_unified_and_edited_ones_left_alone(): void
    {
        $department = DepartmentFactory::new()->create();
        $advisor = ContactpersonFactory::new()->create(['department_id' => $department->id, 'position' => ['de' => 'Sachbearbeiterin', 'en' => 'Advisor']]);
        $tax = ContactpersonFactory::new()->create(['department_id' => $department->id, 'position' => ['de' => 'Sachbearbeiterin Steuer & Beiblätter', 'en' => 'Tax & Supplementary Documents Clerk']]);
        $edited = ContactpersonFactory::new()->create(['department_id' => $department->id, 'position' => ['de' => 'Sachbearbeiter', 'en' => 'Case Officer']]);

        $this->runMigration();

        $this->assertSame('Clerk', $advisor->fresh()->getTranslation('position', 'en'));
        $this->assertSame('Tax & Supplementary Sheets Clerk', $tax->fresh()->getTranslation('position', 'en'));
        $this->assertSame('Case Officer', $edited->fresh()->getTranslation('position', 'en'));
    }

    public function test_chronology_title_and_consent_wording_are_aligned(): void
    {
        $entry = TimelineFactory::new()->create([
            'title' => ['de' => 'Neuausrichtung mit Tradition: Die Gründung der Bontus Immobilienverwaltung', 'en' => 'Bontus Immobilienverwaltung established'],
        ]);
        DB::table('settings')->where('name', 'contactform_terms_en')->update(['payload' => json_encode('I agree to the processing and storage of my data, as well as to being contacted by email or telephone in the course of enquiry processing.')]);

        $this->runMigration();

        $this->assertInstanceOf(Timeline::class, $entry);
        $this->assertStringStartsWith('Renewal with tradition', $entry->fresh()->getTranslation('title', 'en'));
        $this->assertStringContainsString('via email or phone', json_decode(DB::table('settings')->where('name', 'contactform_terms_en')->value('payload')));
    }

    public function test_english_area_names_are_consistent(): void
    {
        app()->setLocale('en');

        $this->assertSame('Technical Services', __('navigation.technology'));
        $this->assertSame('Technical Services', __('Technik'));
        $this->assertSame('Property Management', __('Hausverwaltung'));
        $this->assertSame('estate agents', __('footer.broker'));
    }
}
