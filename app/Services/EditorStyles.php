<?php

namespace App\Services;

use Illuminate\Foundation\Http\Events\RequestHandled;

/**
 * Flux puts the stylesheet of its rich text editor into the head of every page that contains the contact form
 * (Livewire inserts it after the page has been rendered). The editor sits at the bottom of the page, but the
 * stylesheet blocked the first paint of the whole page (about 1.5 s on a slow phone). It is loaded as a preload
 * and applied as soon as it has arrived; without JavaScript it is a normal stylesheet.
 */
class EditorStyles
{
    private const PATTERN = '~<link rel="stylesheet" href="([^"]*/flux/editor\.css[^"]*)"([^>]*)>~';

    public static function defer(RequestHandled $event): void
    {
        $response = $event->response;

        if (! str_contains((string) $response->headers->get('Content-Type'), 'text/html')) {
            return;
        }

        $content = $response->getContent();

        if ($content === false || ! str_contains($content, '/flux/editor.css')) {
            return;
        }

        $response->setContent(preg_replace(
            self::PATTERN,
            '<link rel="preload" as="style" href="$1"$2 onload="this.onload=null;this.rel=\'stylesheet\'">'
            .'<noscript><link rel="stylesheet" href="$1"$2></noscript>',
            $content,
        ));
    }
}
