<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Findings of the Google Search Console check of 2026-11-15: old addresses that Google still lists and that answered
 * 404 (the damage report of the old website, the placeholder service under the Makler and Technik prefix).
 */
class SearchConsoleFindingsRedirectsTest extends TestCase
{
    use RefreshDatabase;

    private function root(): string
    {
        return rtrim(config('app.url'), '/');
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function oldAddresses(): array
    {
        return [
            'damage report' => ['/schadensmeldung', '/service?tab=schadensformular'],
            'damage report with trailing slash' => ['/schadensmeldung/', '/service?tab=schadensformular'],
            'damage report in english' => ['/en/schadensmeldung', '/en/service?tab=schadensformular'],
            'placeholder service' => ['/leistung/alias-qui-vel-ea-repellendus', '/leistung/bauverwaltung'],
            'placeholder service under makler' => ['/makler/leistung/alias-qui-vel-ea-repellendus', '/leistung/bauverwaltung'],
            'placeholder service under technik' => ['/technik/leistung/alias-qui-vel-ea-repellendus', '/leistung/bauverwaltung'],
        ];
    }

    #[DataProvider('oldAddresses')]
    public function test_old_addresses_redirect_permanently(string $from, string $to): void
    {
        $this->get($from)->assertStatus(301)->assertRedirect($this->root().$to);
    }

    public function test_app_stays_a_real_404(): void
    {
        $this->get('/app')->assertNotFound();
    }
}
