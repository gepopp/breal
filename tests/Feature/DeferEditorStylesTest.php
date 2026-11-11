<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class DeferEditorStylesTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_editor_stylesheet_no_longer_blocks_rendering_on_a_real_page(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertDoesNotMatchRegularExpression('~<link rel="stylesheet" href="[^"]*/flux/editor\.css~', preg_replace('~<noscript>.*?</noscript>~s', '', $html));
        $this->assertMatchesRegularExpression('~<link rel="preload" as="style" href="[^"]*/flux/editor\.css\?id=[a-z0-9]+" onload=~', $html);
        $this->assertMatchesRegularExpression('~<noscript><link rel="stylesheet" href="[^"]*/flux/editor\.css\?id=[a-z0-9]+"></noscript>~', $html);
    }

    public function test_other_stylesheets_and_non_html_responses_stay_untouched(): void
    {
        Route::get('/_test/html', fn () => response('<link rel="stylesheet" href="https://x.test/build/app.css"><link rel="stylesheet" href="https://x.test/flux/editor.css?id=abc">'));
        Route::get('/_test/css', fn () => response('/* flux/editor.css */', 200, ['Content-Type' => 'text/css']));

        $html = $this->get('/_test/html')->getContent();
        $this->assertStringContainsString('<link rel="stylesheet" href="https://x.test/build/app.css">', $html);
        $this->assertStringContainsString('<link rel="preload" as="style" href="https://x.test/flux/editor.css?id=abc" onload=', $html);

        $this->get('/_test/css')->assertSee('/* flux/editor.css */', false);
    }
}
