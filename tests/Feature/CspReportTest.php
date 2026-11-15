<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Mockery;
use Tests\TestCase;

class CspReportTest extends TestCase
{
    use RefreshDatabase;

    private function legacyReport(array $overrides = []): string
    {
        return json_encode(['csp-report' => $overrides + [
            'document-uri' => 'https://www.example.at/makler/immobilie/test?x=1',
            'effective-directive' => 'connect-src',
            'violated-directive' => 'connect-src',
            'blocked-uri' => 'https://tracker.example.com/collect',
            'source-file' => 'https://www.example.at/build/assets/app.js',
            'line-number' => 12,
            'disposition' => 'report',
        ]]);
    }

    private function sendReport(string $body, string $type = 'application/csp-report')
    {
        return $this->call('POST', '/csp-report', [], [], [], ['CONTENT_TYPE' => $type], $body);
    }

    public function test_policy_names_the_report_endpoint_for_both_report_mechanisms(): void
    {
        $response = $this->get('/team');
        $policy = $response->headers->get('Content-Security-Policy-Report-Only');

        $this->assertStringContainsString('; report-uri /csp-report; report-to csp-endpoint', $policy);
        $this->assertSame('csp-endpoint="'.url('/csp-report').'"', $response->headers->get('Reporting-Endpoints'));
    }

    public function test_reporting_directives_can_be_switched_off(): void
    {
        config(['security.csp.report_path' => null]);

        $response = $this->get('/team');

        $this->assertStringNotContainsString('report-uri', $response->headers->get('Content-Security-Policy-Report-Only'));
        $response->assertHeaderMissing('Reporting-Endpoints');
    }

    public function test_connect_src_lists_only_the_hosts_the_site_contacts(): void
    {
        $policy = $this->get('/team')->headers->get('Content-Security-Policy-Report-Only');
        preg_match('/connect-src ([^;]+)/', $policy, $match);
        $sources = explode(' ', $match[1]);

        $this->assertNotContains('https:', $sources);
        $this->assertNotContains('wss:', $sources);
        $this->assertContains("'self'", $sources);
        $this->assertContains('https://*.google-analytics.com', $sources);
        $this->assertContains('https://maps.googleapis.com', $sources);
    }

    public function test_a_legacy_report_is_logged_and_answered_without_session_or_cookie(): void
    {
        Log::shouldReceive('channel')->once()->with('csp')->andReturnSelf();
        Log::shouldReceive('info')->once()->with('CSP violation', Mockery::on(fn (array $report): bool => $report['directive'] === 'connect-src'
            && $report['blocked'] === 'https://tracker.example.com/collect'
            && $report['line'] === 12
            && $report['disposition'] === 'report'));

        $response = $this->sendReport($this->legacyReport());

        $response->assertNoContent();
        $this->assertEmpty($response->headers->getCookies());
    }

    public function test_a_reporting_api_list_is_logged(): void
    {
        Log::shouldReceive('channel')->twice()->with('csp')->andReturnSelf();
        Log::shouldReceive('info')->once()->with('CSP violation', Mockery::on(fn (array $report): bool => $report['directive'] === 'img-src' && $report['blocked'] === 'https://a.example.com/x.png'));
        Log::shouldReceive('info')->once()->with('CSP violation', Mockery::on(fn (array $report): bool => $report['directive'] === 'script-src-elem'));

        $reports = [
            ['type' => 'csp-violation', 'body' => ['effectiveDirective' => 'img-src', 'blockedURL' => 'https://a.example.com/x.png', 'documentURL' => 'https://www.example.at/', 'disposition' => 'report']],
            ['type' => 'deprecation', 'body' => ['id' => 'x']],
            ['type' => 'csp-violation', 'body' => ['effectiveDirective' => 'script-src-elem', 'blockedURL' => 'inline', 'documentURL' => 'https://www.example.at/b']],
        ];

        $this->sendReport(json_encode($reports), 'application/reports+json')->assertNoContent();
    }

    public function test_the_same_violation_is_logged_once_per_hour(): void
    {
        Log::shouldReceive('channel')->once()->with('csp')->andReturnSelf();
        Log::shouldReceive('info')->once();

        $this->sendReport($this->legacyReport())->assertNoContent();
        $this->sendReport($this->legacyReport(['line-number' => 99]))->assertNoContent();
    }

    public function test_reports_caused_by_browser_extensions_are_dropped(): void
    {
        Log::shouldReceive('channel')->never();

        $this->sendReport($this->legacyReport(['blocked-uri' => 'chrome-extension://abcdef/inject.js']))->assertNoContent();
        $this->sendReport($this->legacyReport(['source-file' => 'moz-extension://abcdef/content.js', 'blocked-uri' => 'inline']))->assertNoContent();
    }

    public function test_garbage_empty_and_oversized_bodies_are_answered_but_not_logged(): void
    {
        Log::shouldReceive('channel')->never();

        $this->sendReport('not json')->assertNoContent();
        $this->sendReport('')->assertNoContent();
        $this->sendReport('{"csp-report":{"blocked-uri":"x"}}')->assertNoContent();
        $this->sendReport($this->legacyReport(['blocked-uri' => str_repeat('a', 21000)]))->assertNoContent();
    }

    public function test_long_values_are_cut_and_markup_is_removed(): void
    {
        Log::shouldReceive('channel')->once()->with('csp')->andReturnSelf();
        Log::shouldReceive('info')->once()->with('CSP violation', Mockery::on(fn (array $report): bool => strlen($report['blocked']) === 300
            && ! str_contains($report['document'], '<')));

        $this->sendReport($this->legacyReport(['blocked-uri' => 'https://x.example.com/'.str_repeat('a', 500), 'document-uri' => 'https://www.example.at/<script>']))->assertNoContent();
    }

    public function test_only_post_is_allowed(): void
    {
        $this->get('/csp-report')->assertNotFound();
    }
}
