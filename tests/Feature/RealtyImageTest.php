<?php

namespace Tests\Feature;

use App\Livewire\Subparts\RealtyThumbnail;
use App\Models\Realty;
use App\Services\RealtyImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class RealtyImageTest extends TestCase
{
    use RefreshDatabase;

    private const PHOTO = 'https://storage.justimmo.at/thumb/2f0aa01cba6667ff198bcd4d0a911337/fm_h1080_w1920/mDsbwapgcqkPHQS2Sf789K.jpg';

    protected function tearDown(): void
    {
        File::deleteDirectory(public_path('realty-image'));

        parent::tearDown();
    }

    private function jpeg(int $width, int $height): string
    {
        $image = imagecreatetruecolor($width, $height);
        ob_start();
        imagejpeg($image);
        imagedestroy($image);

        return (string) ob_get_clean();
    }

    private function realty(): void
    {
        $attachment = fn (string $url, string $group): array => [
            '@attributes' => ['gruppe' => $group],
            'format' => 'jpg',
            'daten' => ['pfad' => $url],
        ];

        Realty::create([
            'title' => 'Helle Wohnung am Park', 'slug' => 'helle-wohnung', 'objektnr_extern' => '1/1',
            'beschreibung' => '<p>Text</p>', 'nutzungsart' => 'WOHNEN', 'vermarktungsart' => 'miete',
            'data' => [
                'flaechen' => [], 'preise' => [], 'ausstattung' => [], 'zustand_angaben' => [], 'geo' => [], 'freitexte' => [],
                'verwaltung_techn' => [], 'verwaltung_objekt' => [], 'objektkategorie' => [],
                'anhaenge' => ['anhang' => [
                    $attachment(self::PHOTO, 'TITELBILD'),
                    $attachment(str_replace('mDsbwapgcqkPHQS2Sf789K', 'second', self::PHOTO), 'BILD'),
                ]],
                'kontaktperson' => ['vorname' => 'Tanja', 'name' => 'Test', 'email_direkt' => 'vertrieb@bereal-makler.at'],
            ],
        ]);
    }

    public function test_url_points_to_the_own_domain_and_round_trips_to_the_photo(): void
    {
        $url = RealtyImage::url(self::PHOTO, 480);

        $this->assertStringStartsWith(url('/realty-image/480/'), $url);
        $this->assertStringEndsWith('.webp', $url);

        $key = basename($url);

        $this->assertSame(self::PHOTO, RealtyImage::sourceFromKey($key));
    }

    public function test_photos_from_other_hosts_or_with_other_widths_get_no_copy(): void
    {
        $this->assertNull(RealtyImage::url('https://evil.example/thumb/a/b/c.jpg', 480));
        $this->assertNull(RealtyImage::url('https://storage.justimmo.at.evil.example/a.jpg', 480));
        $this->assertNull(RealtyImage::url('https://storage.justimmo.at/../etc/passwd.jpg', 480));
        $this->assertNull(RealtyImage::url(self::PHOTO, 999));
        $this->assertNull(RealtyImage::srcset('https://evil.example/a.jpg', [480, 800]));
    }

    public function test_srcset_lists_every_width(): void
    {
        $srcset = RealtyImage::srcset(self::PHOTO, [480, 800]);

        $this->assertMatchesRegularExpression('#/realty-image/480/[^ ]+\.webp 480w, .*/realty-image/800/[^ ]+\.webp 800w$#', $srcset);
    }

    public function test_route_creates_a_scaled_webp_copy_once_and_serves_it_from_the_own_domain(): void
    {
        Http::fake([self::PHOTO => Http::response($this->jpeg(1920, 1080), 200, ['Content-Type' => 'image/jpeg'])]);

        $path = parse_url(RealtyImage::url(self::PHOTO, 480), PHP_URL_PATH);

        $response = $this->get($path)->assertOk();
        $response->assertHeader('Cache-Control');

        $copy = public_path('realty-image/480/'.basename($path));
        $this->assertFileExists($copy);
        $this->assertSame(480, getimagesize($copy)[0]);
        $this->assertSame('image/webp', getimagesize($copy)['mime']);

        $this->get($path)->assertOk();
        Http::assertSentCount(1);
    }

    public function test_route_does_not_scale_a_small_photo_up(): void
    {
        Http::fake([self::PHOTO => Http::response($this->jpeg(300, 200), 200)]);

        $path = parse_url(RealtyImage::url(self::PHOTO, 800), PHP_URL_PATH);
        $this->get($path)->assertOk();

        $this->assertSame(300, getimagesize(public_path('realty-image/800/'.basename($path)))[0]);
    }

    public function test_route_rejects_unsupported_widths_and_keys_without_any_request(): void
    {
        Http::fake();

        $key = basename(RealtyImage::url(self::PHOTO, 480));

        $this->get('/realty-image/999/'.$key)->assertNotFound();
        $this->get('/realty-image/480/not-a-valid-key.webp')->assertNotFound();
        $this->get('/realty-image/480/'.rtrim(strtr(base64_encode('../../.env.jpg'), '+/', '-_'), '=').'.webp')->assertNotFound();

        Http::assertNothingSent();
    }

    public function test_route_is_not_found_if_the_photo_cannot_be_fetched(): void
    {
        Http::fake([self::PHOTO => Http::response('', 403)]);

        $this->get(parse_url(RealtyImage::url(self::PHOTO, 480), PHP_URL_PATH))->assertNotFound();
    }

    public function test_property_page_gallery_uses_responsive_lazy_images(): void
    {
        $this->realty();

        $html = $this->get('/makler/immobilie/helle-wohnung')->assertOk()->getContent();

        $this->assertStringNotContainsString(' src="'.self::PHOTO.'"', $html);
        $this->assertMatchesRegularExpression('#<img src="[^"]+/realty-image/800/[^"]+"\s+srcset="[^"]*480w[^"]*1280w"[^>]*sizes="[^"]+"[^>]*width="1280" height="720"\s+loading="eager"\s+fetchpriority="high"#s', $html);
        $this->assertMatchesRegularExpression('#<img src="[^"]+/realty-image/800/[^"]+"[^>]*loading="lazy"#s', $html);
        $this->assertMatchesRegularExpression('#<img src="[^"]+/realty-image/160/[^"]+"[^>]*width="160" height="160" loading="lazy"#s', $html);
        $this->assertStringContainsString('data-src="'.self::PHOTO.'"', $html);
    }

    public function test_search_result_thumbnails_use_the_smaller_copies(): void
    {
        $this->realty();

        $html = Livewire::withoutLazyLoading()->test(RealtyThumbnail::class, ['realty' => Realty::firstOrFail()])->html();

        $this->assertStringNotContainsString(' src="'.self::PHOTO.'"', $html);
        $this->assertMatchesRegularExpression('#<img src="[^"]+/realty-image/480/[^"]+"\s+srcset="[^"]*320w[^"]*800w"#', $html);
        $this->assertStringContainsString('href="'.self::PHOTO.'"', $html);
    }
}
