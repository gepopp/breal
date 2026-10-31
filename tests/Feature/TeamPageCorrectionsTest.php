<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TeamPageCorrectionsTest extends TestCase
{
    use RefreshDatabase;

    private function runMigration(): void
    {
        (require database_path('migrations/2026_10_31_220000_fix_team_page_typo_and_placeholder_department.php'))->up();
    }

    private function department(string $company, string $name): int
    {
        return DB::table('departments')->insertGetId(['company' => $company, 'name' => json_encode(['de' => $name, 'en' => $name])]);
    }

    public function test_the_typo_in_the_team_intro_is_corrected(): void
    {
        DB::table('settings')->updateOrInsert(
            ['group' => 'translatable_team', 'name' => 'team_introtext_de'],
            ['locked' => false, 'payload' => json_encode('<p>Wir lassen nicht locker, bis alles zu Ihrer Zufriendheit läuft!</p>')]
        );

        $this->runMigration();

        $text = json_decode(DB::table('settings')->where('group', 'translatable_team')->where('name', 'team_introtext_de')->value('payload'));
        $this->assertStringContainsString('Zufriedenheit', $text);
        $this->assertStringNotContainsString('Zufriendheit', $text);
    }

    public function test_the_placeholder_department_without_contacts_is_removed(): void
    {
        $placeholder = $this->department('Makler', 'soluta');
        $real = $this->department('Makler', 'Vermittlung');

        $this->runMigration();

        $this->assertDatabaseMissing('departments', ['id' => $placeholder]);
        $this->assertDatabaseHas('departments', ['id' => $real]);
    }

    public function test_a_placeholder_department_with_contacts_is_kept(): void
    {
        $placeholder = $this->department('Makler', 'soluta');
        DB::table('contactpeople')->insert(['department_id' => $placeholder, 'name' => 'Test', 'email' => 'test@bereal-immobilien.at', 'phone' => '+43 1 535 36 19']);

        $this->runMigration();

        $this->assertDatabaseHas('departments', ['id' => $placeholder]);
    }
}
