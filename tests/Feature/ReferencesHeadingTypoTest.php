<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ReferencesHeadingTypoTest extends TestCase
{
    use RefreshDatabase;

    private function runMigration(): void
    {
        (require database_path('migrations/2026_11_01_180000_correct_references_heading_typo.php'))->up();
    }

    private function storedHeading(): string
    {
        return json_decode(DB::table('settings')
            ->where('group', 'translatable_hausverwaltung_landingpage')
            ->where('name', 'reference_subheader_de')
            ->value('payload'));
    }

    public function test_the_misspelled_references_heading_is_corrected(): void
    {
        DB::table('settings')
            ->where('group', 'translatable_hausverwaltung_landingpage')
            ->where('name', 'reference_subheader_de')
            ->update(['payload' => json_encode('Unsere Refrenzen - Kontinuität, Leidenschaft & Innovation', JSON_UNESCAPED_UNICODE)]);

        $this->runMigration();
        $this->runMigration();

        $this->assertSame('Unsere Referenzen - Kontinuität, Leidenschaft & Innovation', $this->storedHeading());
    }

    public function test_a_correct_or_edited_heading_is_left_alone(): void
    {
        DB::table('settings')
            ->where('group', 'translatable_hausverwaltung_landingpage')
            ->where('name', 'reference_subheader_de')
            ->update(['payload' => json_encode('Was Kunden über uns sagen', JSON_UNESCAPED_UNICODE)]);

        $this->runMigration();

        $this->assertSame('Was Kunden über uns sagen', $this->storedHeading());
    }
}
