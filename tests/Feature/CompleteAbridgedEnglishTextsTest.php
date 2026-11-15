<?php

namespace Tests\Feature;

use Database\Factories\CompetenceFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CompleteAbridgedEnglishTextsTest extends TestCase
{
    use RefreshDatabase;

    private function runMigration(): void
    {
        (require database_path('migrations/2026_11_15_121000_complete_abridged_english_texts.php'))->up();
    }

    public function test_abridged_english_text_is_replaced_by_the_full_translation(): void
    {
        DB::table('services')->insert([
            'order' => 1,
            'name' => json_encode(['de' => 'Notfallnummern', 'en' => 'Emergency numbers']),
            'company' => 'Technik',
            'description' => json_encode(['de' => '<p>Im Falle eines Gaslecks, Wasserschadens oder Sturmschadens kontaktieren Sie bitte umgehend die Feuerwehr und schicken Sie uns zusätzlich eine Schadensmeldung.</p>', 'en' => '<p>122 Fire service</p>'], JSON_UNESCAPED_UNICODE),
            'slug' => 'notfallnummern',
        ]);

        $this->runMigration();

        $this->assertStringContainsString('gas leak', json_decode(DB::table('services')->where('slug', 'notfallnummern')->value('description'), true)['en']);
    }

    public function test_full_or_edited_english_text_is_left_alone(): void
    {
        $competence = CompetenceFactory::new()->create([
            'name' => ['de' => 'Energieausweis', 'en' => 'Energy performance certificate'],
            'body' => ['de' => '<p>Kurz.</p>', 'en' => '<p>My own, carefully edited English text that is long enough.</p>'],
        ]);

        $this->runMigration();

        $this->assertSame('<p>My own, carefully edited English text that is long enough.</p>', $competence->fresh()->getTranslation('body', 'en'));
    }

    public function test_privacy_policy_gets_all_sections_and_is_idempotent(): void
    {
        $german = json_decode(DB::table('settings')->where('name', 'dpgr_text_de')->value('payload'));
        DB::table('settings')->where('name', 'dpgr_text_en')->update(['payload' => json_encode('<h2>1. Data Protection at a Glance</h2>')]);
        DB::table('settings')->where('name', 'dpgr_text_de')->update(['payload' => json_encode($german.'<p>'.str_repeat('Text ', 200).'</p>')]);

        $this->runMigration();
        $once = DB::table('settings')->where('name', 'dpgr_text_en')->value('payload');
        $this->runMigration();

        $english = json_decode($once);
        $this->assertSame($once, DB::table('settings')->where('name', 'dpgr_text_en')->value('payload'));
        $this->assertStringContainsString('Server log files', $english);
        $this->assertStringContainsString('Right to data portability', $english);
        $this->assertStringContainsString('Enquiries by e-mail, telephone or fax', $english);
    }

    public function test_services_intro_and_hero_headline_are_replaced_only_while_old(): void
    {
        DB::table('settings')->where('name', 'hausverwaltung_leistungen_introtext_en')->update(['payload' => json_encode('<p>Property management is more than just a job for us. Old text.</p>')]);
        DB::table('settings')->where('name', 'immobilien_leistungen_introtext_en')->update(['payload' => json_encode('<p>Edited intro.</p>')]);

        $this->runMigration();

        $this->assertStringContainsString('passionate about property management', json_decode(DB::table('settings')->where('name', 'hausverwaltung_leistungen_introtext_en')->value('payload')));
        $this->assertSame('<p>Edited intro.</p>', json_decode(DB::table('settings')->where('name', 'immobilien_leistungen_introtext_en')->value('payload')));
    }
}
