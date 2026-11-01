<?php

namespace Tests\Feature;

use App\Models\Settings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Tests\TestCase;

class PhotoDescriptionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function media(string $fileName, string $collection = 'hero_images', array $overrides = []): Media
    {
        return Media::forceCreate(array_merge([
            'model_type' => Settings::class,
            'model_id' => 1,
            'uuid' => (string) fake()->uuid(),
            'collection_name' => $collection,
            'name' => $fileName,
            'file_name' => $fileName,
            'mime_type' => 'image/jpeg',
            'disk' => 'public',
            'conversions_disk' => 'public',
            'size' => 1000,
            'manipulations' => [],
            'custom_properties' => [],
            'generated_conversions' => [],
            'responsive_images' => ['media_library_original' => ['urls' => ["{$fileName}___media_library_original_960_640.jpg"]]],
        ], $overrides));
    }

    private function runMigration(): void
    {
        (require database_path('migrations/2026_11_01_210000_describe_hero_and_intro_photos.php'))->up();
    }

    public function test_each_listed_photo_gets_its_own_description_in_both_languages(): void
    {
        $makler = $this->media('luca-dugaro-Z9atS2VR5C0-unsplash.jpg');
        $technik = $this->media('annie-gray-WEWTGkPUVT0-unsplash.jpg', 'hero_image');

        $this->runMigration();
        $this->runMigration();

        $this->assertSame('Prunkvolle Altbaufassaden in der Wiener Innenstadt', $makler->fresh()->getCustomProperty('alt_de'));
        $this->assertStringContainsString('Vienna', $makler->fresh()->getCustomProperty('alt_en'));
        $this->assertNotSame($makler->fresh()->getCustomProperty('alt_de'), $technik->fresh()->getCustomProperty('alt_de'));
    }

    public function test_existing_descriptions_and_other_collections_are_left_alone(): void
    {
        $edited = $this->media('luca-dugaro-Z9atS2VR5C0-unsplash.jpg', 'hero_images', ['custom_properties' => ['alt_de' => 'Vom Kunden']]);
        $about = $this->media('250226-Lukas-Eybel_Tanja_Bachschwöller_F.jpg', 'about_image');

        $this->runMigration();

        $this->assertSame('Vom Kunden', $edited->fresh()->getCustomProperty('alt_de'));
        $this->assertNotNull($edited->fresh()->getCustomProperty('alt_en'));
        $this->assertNull($about->fresh()->getCustomProperty('alt_de'));
    }

    public function test_the_media_description_is_used_only_when_asked_for_and_follows_the_language(): void
    {
        $media = $this->media('photo.jpg', 'hero_images', ['custom_properties' => ['alt_de' => 'Beschreibung', 'alt_en' => 'Description']]);

        app()->setLocale('de');
        $this->assertStringContainsString('alt="Beschreibung"', Blade::render('<x-media-image :media="$media" :describe="true" alt="Generisch"/>', ['media' => $media]));
        $this->assertStringNotContainsString('Generisch', Blade::render('<x-media-image :media="$media" :describe="true" alt="Generisch"/>', ['media' => $media]));

        app()->setLocale('en');
        $this->assertStringContainsString('alt="Description"', Blade::render('<x-media-image :media="$media" :describe="true" alt="Generic"/>', ['media' => $media]));

        $this->assertStringContainsString('alt="Generic"', Blade::render('<x-media-image :media="$media" alt="Generic"/>', ['media' => $media]));
    }
}
