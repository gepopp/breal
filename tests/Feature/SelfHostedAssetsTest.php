<?php

namespace Tests\Feature;

use App\Models\Realty;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SelfHostedAssetsTest extends TestCase
{
    use RefreshDatabase;

    public function test_property_list_no_longer_loads_the_lightbox_from_a_cdn(): void
    {
        Realty::create(['title' => 'Wohnung', 'slug' => 'wohnung', 'nutzungsart' => 'wohnen', 'vermarktungsart' => 'kauf', 'data' => []]);

        $this->get('/makler/immobiliensuche')
            ->assertOk()
            ->assertDontSee('githack', false)
            ->assertDontSee('spotlight.bundle.js', false);
    }

    public function test_security_policy_no_longer_allows_the_removed_hosts(): void
    {
        $policy = $this->get('/team')->headers->get('Content-Security-Policy-Report-Only');

        $this->assertStringNotContainsString('githack', $policy);
        $this->assertStringNotContainsString('typekit', $policy);
    }

    public function test_no_template_refers_to_typekit(): void
    {
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(resource_path('views'), \FilesystemIterator::SKIP_DOTS)) as $file) {
            $this->assertStringNotContainsString('typekit', strtolower((string) file_get_contents($file->getPathname())), $file->getPathname());
        }
    }

    public function test_spotlight_is_part_of_the_build_and_loaded_on_demand(): void
    {
        $app = file_get_contents(resource_path('js/app.js'));

        $this->assertStringContainsString("import('./spotlight.js')", $app);
        $this->assertFileExists(resource_path('js/spotlight.js'));
        $this->assertNotEmpty(glob(public_path('build/assets/spotlight-*.js')), 'run npm run build');
    }
}
