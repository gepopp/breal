<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LandingHeadingsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<int, string>
     */
    private function h1s(string $path): array
    {
        $this->get($path)->assertOk();
        preg_match_all('/<h1\b[^>]*>(.*?)<\/h1>/s', $this->get($path)->getContent(), $matches);

        return array_map(fn (string $html): string => trim(preg_replace('/\s+/', ' ', strip_tags($html))), $matches[1]);
    }

    public function test_service_page_has_a_descriptive_heading_in_both_languages(): void
    {
        $this->assertSame(['Service und Formulare für Eigentümer:innen und Bewohner:innen'], $this->h1s('/service'));
        $this->assertSame(['Service and forms for owners and residents'], $this->h1s('/en/service'));
    }

    public function test_the_heading_migration_keeps_headings_edited_in_the_admin(): void
    {
        DB::table('settings')->where('group', 'translatable_services')->where('name', 'services_subheader_de')
            ->update(['payload' => json_encode('Eigene Überschrift')]);

        (require database_path('migrations/2026_11_01_170000_give_service_page_a_descriptive_heading.php'))->up();

        $this->assertSame(['Eigene Überschrift'], $this->h1s('/service'));
    }

    public function test_the_tagged_line_is_the_h1_on_the_three_landing_pages_and_the_sentence_is_only_a_paragraph(): void
    {
        foreach (['/' => 'be welcome', '/makler' => 'be realtor', '/technik' => 'be engineering'] as $path => $tag) {
            $h1s = $this->h1s($path);

            $this->assertCount(1, $h1s, $path);
            $this->assertSame($tag, $h1s[0], $path);
        }
    }
}
