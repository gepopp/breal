<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_send_the_basic_hardening_headers(): void
    {
        $response = $this->get('/team')->assertOk();

        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $this->assertStringContainsString('camera=()', $response->headers->get('Permissions-Policy'));
    }

    public function test_error_pages_and_redirects_get_the_headers_too(): void
    {
        $this->get('/gibt-es-nicht')->assertNotFound()->assertHeader('X-Content-Type-Options', 'nosniff');
        $this->get('/ueber-uns')->assertHeader('X-Content-Type-Options', 'nosniff');
    }

    public function test_content_security_policy_is_report_only_by_default(): void
    {
        $response = $this->get('/team');

        $response->assertHeaderMissing('Content-Security-Policy');
        $policy = $response->headers->get('Content-Security-Policy-Report-Only');

        $this->assertStringContainsString("default-src 'self'", $policy);
        $this->assertStringContainsString('https://player.vimeo.com', $policy);
        $this->assertStringContainsString("frame-ancestors 'self'", $policy);
        $this->assertStringContainsString("object-src 'none'", $policy);
    }

    public function test_content_security_policy_can_be_enforced_or_switched_off(): void
    {
        config(['security.csp.mode' => 'enforce']);
        $this->get('/team')->assertHeader('Content-Security-Policy')->assertHeaderMissing('Content-Security-Policy-Report-Only');

        config(['security.csp.mode' => 'off']);
        $this->get('/team')->assertHeaderMissing('Content-Security-Policy')->assertHeaderMissing('Content-Security-Policy-Report-Only');
    }

    public function test_hsts_is_only_sent_for_https_requests_in_production(): void
    {
        $this->get('/team')->assertHeaderMissing('Strict-Transport-Security');

        $this->app['env'] = 'production';
        $this->get('http://localhost/team')->assertHeaderMissing('Strict-Transport-Security');

        $response = $this->get('https://localhost/team');
        $this->assertSame('max-age=31536000', $response->headers->get('Strict-Transport-Security'));
    }

    public function test_hsts_can_be_disabled_with_a_zero_max_age(): void
    {
        $this->app['env'] = 'production';
        config(['security.hsts.max_age' => 0]);

        $this->get('https://localhost/team')->assertHeaderMissing('Strict-Transport-Security');
    }
}
