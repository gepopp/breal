<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrivacyProviderInventoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_german_privacy_page_lists_every_provider_and_cookie(): void
    {
        $response = $this->get('/datenschutz')->assertOk();

        $response->assertSee('5. Eingesetzte Dienste und Empfänger im Überblick');

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

        $response->assertSee('5. Services and recipients at a glance');

        foreach (['Google Analytics 4', 'Google Maps', 'Vimeo', 'Hetzner Online GmbH', 'Resend, Inc.', 'LinkedIn', '_ga', 'google_maps', '14 days', '360 days'] as $term) {
            $response->assertSee($term, false);
        }

        $response->assertSee('Only when you open the video');
        $response->assertDontSee('Eingesetzte Dienste');
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
