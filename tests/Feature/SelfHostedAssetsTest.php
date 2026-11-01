<?php

namespace Tests\Feature;

use App\Models\Realty;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
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

    public function test_swiper_comes_from_the_bundle_not_from_a_cdn(): void
    {
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(resource_path('views'), \FilesystemIterator::SKIP_DOTS)) as $file) {
            $this->assertStringNotContainsString('jsdelivr', (string) file_get_contents($file->getPathname()), $file->getPathname());
            $this->assertStringNotContainsString('unpkg.com', (string) file_get_contents($file->getPathname()), $file->getPathname());
        }

        $this->assertStringContainsString("import 'swiper/css/bundle'", file_get_contents(resource_path('js/app.js')));

        $css = collect(glob(public_path('build/assets/app-*.css')))->map(fn (string $file): string => file_get_contents($file))->implode('');
        $this->assertStringContainsString('.swiper-wrapper', $css, 'run npm run build');

        $policy = $this->get('/team')->headers->get('Content-Security-Policy-Report-Only');
        $this->assertStringNotContainsString('jsdelivr', $policy);
        $this->assertStringNotContainsString('unpkg', $policy);
    }

    public function test_poppins_is_served_from_the_build_and_not_from_google(): void
    {
        $css = collect(glob(public_path('build/assets/app-*.css')))->map(fn (string $file): string => file_get_contents($file))->implode('');

        $this->assertStringNotContainsString('fonts.googleapis.com', $css);
        $this->assertStringNotContainsString('fonts.gstatic.com', $css);
        $this->assertStringContainsString('font-family:Poppins', str_replace(' ', '', $css), 'run npm run build');
        $this->assertNotEmpty(glob(public_path('build/assets/poppins-latin-400-normal-*.woff2')));

        $policy = $this->get('/team')->headers->get('Content-Security-Policy-Report-Only');
        $this->assertStringNotContainsString('fonts.g', $policy);
    }

    public function test_video_embed_is_not_part_of_the_page_until_the_modal_is_opened(): void
    {
        $code = '<div style="padding:56.25% 0 0 0;position:relative;"><iframe src="https://player.vimeo.com/video/1?badge=0" allowfullscreen></iframe></div>'
            .'<script src="https://player.vimeo.com/api/player.js"></script>';

        $html = Blade::render('<x-click-to-load-embed modal="video" :code="$code"/>', ['code' => $code]);

        $this->assertStringNotContainsString('<iframe', $html);
        $this->assertStringNotContainsString('<script', $html);
        $this->assertStringNotContainsString('player.js', $html, 'the provider script must be stripped');
        $this->assertStringContainsString('x-on:modal-show.document', $html);
        $this->assertStringContainsString('x-on:modal-close.document', $html);
        $this->assertStringContainsString('<template x-if="loaded">', $html);
        $this->assertStringContainsString('player.vimeo.com\/video\/1', $html, 'the embed itself is kept for loading on demand');
    }

    public function test_the_three_video_modals_use_the_click_to_load_component(): void
    {
        foreach (['about', 'technik-about', 'makler-c-t-a'] as $view) {
            $template = file_get_contents(resource_path("views/livewire/parts/{$view}.blade.php"));

            $this->assertStringContainsString('<x-click-to-load-embed modal="video"', $template);
            $this->assertStringNotContainsString('video_embed_code !!}', $template);
        }
    }

    public function test_slider_scripts_wait_for_the_bundled_swiper(): void
    {
        $this->get('/team')->assertSee('window.onSwiper = function', false);

        foreach (['parts/landing-hero', 'parts/references', 'parts/technik-hero', 'parts/technik-text-image', 'parts/image-text', 'subparts/realty-slider'] as $view) {
            $template = file_get_contents(resource_path("views/livewire/{$view}.blade.php"));

            $this->assertStringContainsString('onSwiper(() => {', $template, $view);
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
