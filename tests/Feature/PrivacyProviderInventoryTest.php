<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PrivacyProviderInventoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_german_privacy_page_lists_every_provider_and_cookie(): void
    {
        $response = $this->get('/datenschutz')->assertOk();

        $response->assertSee('4. Eingesetzte Dienste und Empfänger im Überblick');

        foreach (['Google Analytics 4', 'Google Maps', 'Vimeo', 'Hetzner Online GmbH', 'Resend, Inc.', 'LinkedIn', 'Kundenportal', '_ga', '_gid', 'google_maps', 'XSRF-TOKEN', '_cookie_consent', '14 Tage', '360 Tage'] as $term) {
            $response->assertSee($term, false);
        }

        $response->assertSee('Erst wenn Sie das Video öffnen');
        $response->assertSee('Erst nachdem Sie optionale Cookies akzeptiert haben');
        $response->assertSee('Nur nach Ihrer Einwilligung in die Kategorie');
    }

    public function test_english_privacy_page_has_the_same_overview(): void
    {
        $response = $this->get('/en/datenschutz')->assertOk();

        $response->assertSee('4. Services and recipients at a glance');

        foreach (['Google Analytics 4', 'Google Maps', 'Vimeo', 'Hetzner Online GmbH', 'Resend, Inc.', 'LinkedIn', '_ga', 'google_maps', '14 days', '360 days'] as $term) {
            $response->assertSee($term, false);
        }

        $response->assertSee('Only when you open the video');
        $response->assertDontSee('Eingesetzte Dienste');
    }

    private function policyText(string $name): string
    {
        return json_decode(DB::table('settings')->where('group', 'translatable_legal')->where('name', $name)->value('payload'));
    }

    private function storePolicyText(string $name, string $html): void
    {
        DB::table('settings')->updateOrInsert(['group' => 'translatable_legal', 'name' => $name], ['locked' => false, 'payload' => json_encode($html)]);
    }

    public function test_outdated_sections_are_cut_out_of_the_stored_texts(): void
    {
        $this->storePolicyText('dpgr_text_de', '<h2>3. Datenerfassung auf dieser Website</h2><h3>Cookies</h3><p>Text</p><h3>Einwilligung mit Borlabs Cookie</h3><p>Borlabs</p><h3>Kontaktformular</h3><p>Formular</p><h3>Google Forms</h3><p>Forms</p><h2>4. Plugins und Tools</h2><h3>Google Web Fonts</h3><p>Fonts</p><h3>Google Maps</h3><p>Maps</p>');
        $this->storePolicyText('dpgr_text_en', '<h2>3. Data collection on this website</h2><h3>Contact form</h3><p>Form</p><h3>Google Forms</h3><p>Forms</p><h2>4. Plugins and Tools</h2><h3>Google Maps</h3><p>Maps</p>');

        (require database_path('migrations/2026_11_01_150000_remove_outdated_sections_from_privacy_policy.php'))->up();

        $german = $this->policyText('dpgr_text_de');
        $this->assertSame('<h2>3. Datenerfassung auf dieser Website</h2><h3>Cookies</h3><p>Text</p><h3>Kontaktformular</h3><p>Formular</p>', $german);
        $this->assertSame('<h2>3. Data collection on this website</h2><h3>Contact form</h3><p>Form</p>', $this->policyText('dpgr_text_en'));
    }

    public function test_a_text_without_those_sections_is_left_alone_and_the_migration_can_run_twice(): void
    {
        $html = '<h2>1. Datenschutz</h2><p>Eigener Text mit Google Analytics.</p><h3>Kontaktformular</h3><p>Formular</p>';
        $this->storePolicyText('dpgr_text_de', $html);

        $migration = require database_path('migrations/2026_11_01_150000_remove_outdated_sections_from_privacy_policy.php');
        $migration->up();
        $migration->up();

        $this->assertSame($html, $this->policyText('dpgr_text_de'));
    }

    public function test_default_privacy_texts_and_the_page_no_longer_mention_removed_services(): void
    {
        $defaults = file_get_contents(base_path('app/Settings/LegalSettings.php'));

        foreach (['Borlabs', 'Google Forms', 'Google Web Fonts', 'Plugins und Tools'] as $term) {
            $this->assertStringNotContainsString($term, $defaults, $term);
        }

        $page = $this->get('/datenschutz')->getContent();
        $this->assertStringNotContainsString('Borlabs', $page);
        $this->assertStringNotContainsString('Google Forms', $page);
        $this->assertStringNotContainsString('Google Web Fonts', $page);
    }

    public function test_hosting_is_described_with_ninc_and_netcup(): void
    {
        $this->get('/datenschutz')->assertSee('NINC')->assertSee('netcup GmbH, Karlsruhe, Deutschland');
        $this->get('/en/datenschutz')->assertSee('NINC')->assertSee('netcup GmbH, Karlsruhe, Germany');
    }

    public function test_overview_names_no_service_that_the_site_does_not_load(): void
    {
        $html = $this->get('/datenschutz')->getContent();
        $overview = substr($html, (int) strpos($html, 'provider-inventory'));

        foreach (['Typekit', 'Adobe', 'jsDelivr', 'unpkg', 'githack', 'Facebook', 'Instagram'] as $notUsed) {
            $this->assertStringNotContainsString($notUsed, $overview, $notUsed);
        }
    }

    public function test_every_third_party_host_in_the_policy_is_covered_by_the_overview(): void
    {
        $policy = config('security.csp.directives');
        $hosts = collect($policy)->flatten()->filter(fn (string $source): bool => str_starts_with($source, 'https://') && ! in_array($source, ['https:']))->values();

        $overview = file_get_contents(resource_path('views/legal/provider-inventory-de.blade.php'));
        $names = ['www.googletagmanager.com' => 'Google Tag Manager', 'maps.googleapis.com' => 'Google Maps', 'player.vimeo.com' => 'Vimeo'];

        foreach ($hosts as $source) {
            $host = parse_url($source, PHP_URL_HOST);
            $this->assertArrayHasKey($host, $names, "$host is allowed by the Content-Security-Policy, add it to the privacy overview and this test");
            $this->assertStringContainsString($names[$host], $overview, $host);
        }
    }
}
