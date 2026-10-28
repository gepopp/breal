<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_generates_without_undefined_route(): void
    {
        $response = $this->get('sitemap.xml');

        $response->assertStatus(200);
        $this->assertFileDoesNotExist(public_path('sitemap.xml'));
    }
}
