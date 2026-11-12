<?php

namespace Tests\Feature;

use App\Services\PreloadedImages;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Tests\TestCase;

class PreloadedImagesTest extends TestCase
{
    use RefreshDatabase;

    private function heroMedia(): Media
    {
        return Media::create([
            'model_type' => 'App\\Models\\Settings',
            'model_id' => 1,
            'uuid' => (string) Str::uuid(),
            'collection_name' => 'hero',
            'name' => 'hero',
            'file_name' => 'hero.jpg',
            'mime_type' => 'image/jpeg',
            'disk' => 'public',
            'conversions_disk' => 'public',
            'size' => 1000,
            'manipulations' => [],
            'custom_properties' => [],
            'generated_conversions' => [],
            'responsive_images' => [],
        ]);
    }

    public function test_a_priority_image_is_announced_for_the_head_and_a_lazy_one_is_not(): void
    {
        $media = $this->heroMedia();

        $this->blade('<x-media-image :media="$media" :priority="true" sizes="100vw" />', ['media' => $media]);
        $this->assertCount(1, app(PreloadedImages::class)->all());

        $other = $this->heroMedia();
        $this->blade('<x-media-image :media="$media" />', ['media' => $other]);
        $this->assertCount(1, app(PreloadedImages::class)->all());
    }

    public function test_the_layout_puts_a_preload_with_the_responsive_candidates_into_the_head(): void
    {
        app(PreloadedImages::class)->add('https://cdn.test/hero.webp', 'https://cdn.test/hero-600.webp 600w, https://cdn.test/hero-900.webp 900w', '(min-width: 768px) 50vw, 100vw');

        $html = (string) $this->blade('<x-layouts.site>Inhalt</x-layouts.site>');
        $head = substr($html, 0, strpos($html, '</head>'));

        $this->assertStringContainsString('<link rel="preload" as="image" href="https://cdn.test/hero.webp"', $head);
        $this->assertStringContainsString('imagesrcset="https://cdn.test/hero-600.webp 600w, https://cdn.test/hero-900.webp 900w"', $head);
        $this->assertStringContainsString('imagesizes="(min-width: 768px) 50vw, 100vw"', $head);
        $this->assertStringContainsString('fetchpriority="high"', $head);
    }

    public function test_nothing_is_preloaded_when_the_page_has_no_priority_image(): void
    {
        $html = (string) $this->blade('<x-layouts.site>Inhalt</x-layouts.site>');

        $this->assertStringNotContainsString('rel="preload" as="image"', $html);
    }
}
