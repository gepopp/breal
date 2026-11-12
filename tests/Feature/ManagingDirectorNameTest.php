<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ManagingDirectorNameTest extends TestCase
{
    use RefreshDatabase;

    private function runMigration(): void
    {
        (require database_path('migrations/2026_11_01_140000_use_full_name_of_tanja_bachschwoeller_eybel.php'))->up();
    }

    private function setting(string $group, string $name, string $text): void
    {
        DB::table('settings')->updateOrInsert(['group' => $group, 'name' => $name], ['locked' => false, 'payload' => json_encode($text)]);
    }

    private function storedSetting(string $group, string $name): string
    {
        return json_decode(DB::table('settings')->where('group', $group)->where('name', $name)->value('payload'));
    }

    public function test_stored_texts_use_the_full_name(): void
    {
        $this->setting('translatable_makler', 'about_heading_de', 'Geschäftsführerin und Eigentümerin Tanja Bachschwöller, BA');
        $this->setting('translatable_makler', 'about_text_en', '<p>In spring 2021, Tanja Bachschwöller founded the brokerage with Lukas Eybel.</p>');
        $this->setting('translatable_hausverwaltung_landingpage', 'timeline_intro_de', '<p>fortgeführt von Tanja Bachschwöller und Lukas Eybel</p>');

        $this->runMigration();

        $this->assertSame('Geschäftsführerin und Eigentümerin Tanja Bachschwöller-Eybel, BA', $this->storedSetting('translatable_makler', 'about_heading_de'));
        $this->assertStringContainsString('Tanja Bachschwöller-Eybel founded', $this->storedSetting('translatable_makler', 'about_text_en'));
        $this->assertStringContainsString('Tanja Bachschwöller-Eybel und Lukas Eybel', $this->storedSetting('translatable_hausverwaltung_landingpage', 'timeline_intro_de'));
    }

    public function test_a_name_already_written_in_full_and_other_texts_stay_as_they_are(): void
    {
        $this->setting('translatable_makler', 'about_heading_de', 'Tanja Bachschwöller-Eybel, BA');
        $this->setting('translatable_makler', 'about_text_de', 'Bachschwöller und Eybel sind die Inhaber.');

        $this->runMigration();
        $this->runMigration();

        $this->assertSame('Tanja Bachschwöller-Eybel, BA', $this->storedSetting('translatable_makler', 'about_heading_de'));
        $this->assertSame('Bachschwöller und Eybel sind die Inhaber.', $this->storedSetting('translatable_makler', 'about_text_de'));
    }

    public function test_timeline_entries_are_corrected_in_both_languages(): void
    {
        DB::table('timelines')->insert([
            'title' => json_encode(['de' => 'Heute', 'en' => 'Today']),
            'description' => json_encode(['de' => 'Geleitet von Tanja Bachschwöller.', 'en' => 'Led by Tanja Bachschwöller.'], JSON_UNESCAPED_UNICODE),
            'year' => 2024,
        ]);

        $this->runMigration();

        $description = json_decode(DB::table('timelines')->value('description'), true);
        $this->assertSame('Geleitet von Tanja Bachschwöller-Eybel.', $description['de']);
        $this->assertSame('Led by Tanja Bachschwöller-Eybel.', $description['en']);
    }

    public function test_default_texts_and_llms_file_use_the_full_name(): void
    {
        foreach (['app/Settings/MaklerLandingpageSettings.php', 'app/Settings/HausverwaltungLandingpageSettings.php', 'public/llms.txt'] as $file) {
            $this->assertSame(0, preg_match('/Tanja Bachschwöller(?!-Eybel)/u', file_get_contents(base_path($file))), $file);
        }
    }

    public function test_makler_about_page_shows_the_full_name(): void
    {
        $this->get('/makler/ueber-uns')->assertOk()->assertSee('Tanja Bachschwöller-Eybel');
        $this->get('/en/makler/ueber-uns')->assertOk()->assertSee('Tanja Bachschwöller-Eybel');
    }
}
