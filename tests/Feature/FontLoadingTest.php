<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FontLoadingTest extends TestCase
{
    use RefreshDatabase;

    public function test_every_font_face_keeps_the_text_visible_while_the_font_loads(): void
    {
        $css = file_get_contents(resource_path('css/app.css'));

        preg_match_all('/@font-face\s*\{(.*?)\}/s', $css, $faces);

        $this->assertNotEmpty($faces[1]);

        foreach ($faces[1] as $face) {
            $this->assertStringContainsString('font-display: swap', $face, 'A font without font-display hides the text until it has loaded');
        }
    }

    public function test_the_fonts_of_the_first_screen_are_preloaded_from_the_head(): void
    {
        $html = (string) $this->blade('<x-layouts.site>Inhalt</x-layouts.site>');
        $head = substr($html, 0, strpos($html, '</head>'));

        preg_match_all('~<link rel="preload" as="font" type="font/woff2" href="([^"]+)" crossorigin>~', $head, $preloads);

        $this->assertCount(2, $preloads[1]);
        $this->assertStringContainsString('EmBauhaus-W00-Bold', $preloads[1][0]);
        $this->assertStringContainsString('poppins-latin-400-normal', $preloads[1][1]);

        // The preloaded files must be the ones the stylesheet asks for, otherwise they would be fetched twice.
        $built = file_get_contents(public_path('build/assets/'.basename(json_decode(file_get_contents(public_path('build/manifest.json')), true)['resources/css/app.css']['file'])));

        foreach ($preloads[1] as $url) {
            $this->assertStringContainsString(basename($url), $built);
        }
    }
}
