<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AccessibilityStatementTest extends TestCase
{
    use RefreshDatabase;

    private function runMigration(): void
    {
        (require database_path('migrations/2026_11_01_190000_replace_accessibility_statement.php'))->up();
    }

    private function store(string $name, string $text): void
    {
        DB::table('settings')->where('group', 'translatable_legal')->where('name', $name)
            ->update(['payload' => json_encode($text, JSON_UNESCAPED_UNICODE)]);
    }

    public function test_the_old_claim_of_full_conformity_is_replaced_in_both_languages(): void
    {
        $this->store('accessability_text_de', '<p>Diese Website ist … vollständig vereinbar.</p>');
        $this->store('accessability_text_en', '<p>This website is fully compliant …</p>');

        $this->runMigration();
        $this->runMigration();

        $this->get('/barrierefreiheit')->assertOk()
            ->assertSee('teilweise vereinbar')
            ->assertDontSee('vollständig vereinbar')
            ->assertDontSee('WZG');
        $this->get('/en/barrierefreiheit')->assertOk()
            ->assertSee('partially conforms')
            ->assertDontSee('fully compliant');
    }

    public function test_an_edited_statement_is_left_alone(): void
    {
        $this->store('accessability_text_de', '<p>Vom Kunden geändert.</p>');

        $this->runMigration();

        $this->get('/barrierefreiheit')->assertOk()->assertSee('Vom Kunden geändert.');
    }
}
