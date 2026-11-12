<?php

namespace App\Services;

/**
 * Collects the images that the page needs for its first screen (the hero), so that the layout can tell the
 * browser about them in the head. Without the hint the browser only finds the image once it has parsed the body,
 * which is about 400 ms later on a slow phone.
 *
 * Registered as a scoped service: one collection per request.
 */
class PreloadedImages
{
    /**
     * @var array<string, array{src: string, srcset: ?string, sizes: ?string}>
     */
    private array $images = [];

    public function add(string $src, ?string $srcset = null, ?string $sizes = null): void
    {
        $this->images[$src] = ['src' => $src, 'srcset' => $srcset, 'sizes' => $sizes];
    }

    /**
     * @return array<string, array{src: string, srcset: ?string, sizes: ?string}>
     */
    public function all(): array
    {
        return $this->images;
    }
}
