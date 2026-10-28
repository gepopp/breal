<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitemapReferenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_robots_txt_references_the_sitemap_and_allows_all_crawlers(): void
    {
        $robots = file_get_contents(public_path('robots.txt'));

        $this->assertStringContainsString("User-agent: *\nAllow: /", $robots);
        $this->assertStringContainsString('Sitemap: https://www.bereal-immobilien.at/sitemap.xml', $robots);
        $this->assertStringNotContainsString('Disallow: /', $robots);
    }

    public function test_german_pages_link_to_the_sitemap_in_the_head(): void
    {
        $response = $this->get('/kontakt');

        $response->assertSee('<link rel="sitemap" type="application/xml" title="Sitemap" href="'.url('sitemap.xml').'">', false);
    }

    public function test_english_pages_link_to_the_unprefixed_sitemap_in_the_head(): void
    {
        $response = $this->get('/en/kontakt');

        $response->assertSee('<link rel="sitemap" type="application/xml" title="Sitemap" href="'.url('sitemap.xml').'">', false);
        $response->assertDontSee('/en/sitemap.xml', false);
    }
}
