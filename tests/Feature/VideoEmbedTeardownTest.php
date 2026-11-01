<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class VideoEmbedTeardownTest extends TestCase
{
    public function test_the_embed_is_removed_whenever_its_dialog_closes(): void
    {
        $html = Blade::render('<x-click-to-load-embed modal="video" code="<iframe src=\'https://player.vimeo.com/video/1\'></iframe><script src=\'https://player.vimeo.com/api/player.js\'></script>"/>');

        $this->assertStringContainsString("closest('dialog')?.addEventListener('toggle'", $html);
        $this->assertStringContainsString("event.newState === 'closed'", $html);
        $this->assertStringContainsString('x-on:modal-close.document', $html);
        $this->assertStringContainsString('<template x-if="loaded">', $html);
        $this->assertStringNotContainsString('player.js', $html);
    }
}
