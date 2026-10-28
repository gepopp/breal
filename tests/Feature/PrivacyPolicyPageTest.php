<?php

namespace Tests\Feature;

use App\Settings\PagesSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrivacyPolicyPageTest extends TestCase
{
    use RefreshDatabase;

    private const POLICY_HTML = '<h2>Datenschutz auf einen Blick</h2><p>Allgemeine Hinweise zum Datenschutz.</p>';

    protected function setUp(): void
    {
        parent::setUp();

        $settings = app(PagesSettings::class);
        $settings->dpgr_text = self::POLICY_HTML;
        $settings->save();
    }

    public function test_policy_text_is_part_of_the_server_rendered_main_content(): void
    {
        $response = $this->get('/datenschutz');

        $response->assertOk();

        preg_match('#<main[^>]*>(.*?)</main>#s', $response->getContent(), $main);

        $this->assertArrayHasKey(1, $main);
        $this->assertStringContainsString(self::POLICY_HTML, $main[1]);
    }

    public function test_policy_page_has_a_real_title_description_and_a_single_h1(): void
    {
        $response = $this->get('/datenschutz');

        $response->assertSee('<title>Datenschutzerklärung – be real Immobilien</title>', false);
        $response->assertDontSee('Bontus');
        $response->assertSee('<link rel="canonical" href="'.url('datenschutz').'">', false);
        $this->assertSame(1, substr_count($response->getContent(), '<h1'));
        $response->assertSee('Datenschutzerklärung der be real Immobilienmanagement GmbH');
        $response->assertDontSee('Immobilienmanagment');
    }

    public function test_english_policy_page_has_an_english_title_and_heading(): void
    {
        $response = $this->get('/en/datenschutz');

        $response->assertOk();
        $response->assertSee('<title>Privacy Policy – be real Immobilien</title>', false);
        $response->assertSee('Privacy policy of be real Immobilienmanagement GmbH');
        $response->assertSee('<link rel="alternate" hreflang="de-AT" href="'.url('datenschutz').'">', false);
    }
}
