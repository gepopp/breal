<?php

namespace Tests\Feature;

use App\Models\Reference;
use App\Models\Settings;
use App\Models\Timeline;
use App\Services\MediaImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Tests\TestCase;

class MediaImageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function media(array $overrides = []): Media
    {
        $original = collect([6000, 4200, 1920, 1440, 960, 480])->map(fn (int $width): string => "photo___media_library_original_{$width}_".intdiv($width * 2, 3).'.jpg')->all();

        return Media::forceCreate(array_merge([
            'model_type' => Settings::class,
            'model_id' => 1,
            'uuid' => (string) fake()->uuid(),
            'collection_name' => 'hero_image',
            'name' => 'photo',
            'file_name' => 'photo.jpg',
            'mime_type' => 'image/jpeg',
            'disk' => 'public',
            'conversions_disk' => 'public',
            'size' => 6_000_000,
            'manipulations' => [],
            'custom_properties' => [],
            'generated_conversions' => [],
            'responsive_images' => ['media_library_original' => ['urls' => $original]],
        ], $overrides));
    }

    public function test_the_original_is_capped_when_the_web_conversion_does_not_exist_yet(): void
    {
        $attributes = MediaImage::attributes($this->media());

        $this->assertStringNotContainsString('6000w', $attributes['srcset']);
        $this->assertStringNotContainsString('4200w', $attributes['srcset']);
        $this->assertStringContainsString('1920w', $attributes['srcset']);
        $this->assertStringContainsString('480w', $attributes['srcset']);
        $this->assertStringEndsWith('photo___media_library_original_1920_1280.jpg', $attributes['src']);
        $this->assertSame([1920, 1280], [$attributes['width'], $attributes['height']]);
    }

    public function test_the_web_conversion_is_used_once_it_is_generated(): void
    {
        $media = $this->media([
            'generated_conversions' => ['web' => true],
            'responsive_images' => ['web' => ['urls' => ['photo-web___web_1920_1280.webp', 'photo-web___web_960_640.webp']]],
        ]);

        $attributes = MediaImage::attributes($media);

        $this->assertStringEndsWith('photo-web.webp', $attributes['src']);
        $this->assertStringContainsString('photo-web___web_960_640.webp 960w', $attributes['srcset']);
        $this->assertStringContainsString('photo-web___web_1920_1280.webp 1920w', $attributes['srcset']);
        $this->assertStringNotContainsString('media_library_original', $attributes['srcset']);
    }

    public function test_an_image_without_responsive_files_falls_back_to_the_original_url(): void
    {
        $attributes = MediaImage::attributes($this->media(['responsive_images' => []]));

        $this->assertStringEndsWith('photo.jpg', $attributes['src']);
        $this->assertNull($attributes['srcset']);
        $this->assertNull($attributes['width']);
    }

    public function test_the_component_lazy_loads_by_default_and_prioritises_on_request(): void
    {
        $media = $this->media();

        $lazy = Blade::render('<x-media-image :media="$media" sizes="50vw" alt="Haus" class="w-full"/>', ['media' => $media]);
        $priority = Blade::render('<x-media-image :media="$media" :priority="true" alt="Haus"/>', ['media' => $media]);

        $this->assertStringContainsString('loading="lazy"', $lazy);
        $this->assertStringContainsString('sizes="50vw"', $lazy);
        $this->assertStringContainsString('alt="Haus"', $lazy);
        $this->assertStringContainsString('class="w-full"', $lazy);
        $this->assertStringContainsString('width="1920" height="1280"', $lazy);
        $this->assertStringNotContainsString('fetchpriority', $lazy);
        $this->assertStringContainsString('loading="eager"', $priority);
        $this->assertStringContainsString('fetchpriority="high"', $priority);
    }

    public function test_the_component_renders_nothing_without_media(): void
    {
        $this->assertSame('', trim(Blade::render('<x-media-image :media="null" alt="x"/>')));
    }

    public function test_models_with_public_images_register_the_web_conversion(): void
    {
        foreach ([new Settings, new Timeline, new Reference] as $model) {
            $model->registerMediaConversions();

            $this->assertContains(MediaImage::CONVERSION, collect($model->mediaConversions)->map->getName()->all(), $model::class);
        }
    }
}
