<?php

namespace Tests\Feature;

use Tests\TestCase;

class BuildAssetCachingTest extends TestCase
{
    public function test_hashed_build_assets_are_cached_for_a_year(): void
    {
        $htaccess = file_get_contents(public_path('.htaccess'));

        $this->assertStringContainsString('max-age=31536000, immutable', $htaccess);
        $this->assertStringContainsString('mod_headers.c', $htaccess);
        $this->assertStringContainsString('^/build/assets/', $htaccess);
    }
}
