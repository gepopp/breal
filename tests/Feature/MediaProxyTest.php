<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * The object storage needs over a second to the first byte, so media is handed out from the own domain: the first
 * request copies the file from the bucket into public/media, the web server serves it from there afterwards.
 */
class MediaProxyTest extends TestCase
{
    use RefreshDatabase;

    private string $cacheDirectory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cacheDirectory = public_path('media');
        File::deleteDirectory($this->cacheDirectory);

        config(['media-library.disk_name' => 's3']);
        Storage::fake('s3');
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->cacheDirectory);

        parent::tearDown();
    }

    public function test_file_is_copied_from_the_bucket_on_first_request(): void
    {
        Storage::disk('s3')->put('33/responsive-images/hero___web_787_525.webp', 'image-bytes');

        $response = $this->get('/media/33/responsive-images/hero___web_787_525.webp');

        $response->assertOk();
        $this->assertStringContainsString('immutable', $response->headers->get('Cache-Control'));
        $this->assertFileExists(public_path('media/33/responsive-images/hero___web_787_525.webp'));
        $this->assertSame('image-bytes', file_get_contents(public_path('media/33/responsive-images/hero___web_787_525.webp')));
    }

    public function test_file_with_umlaut_in_the_name_is_found(): void
    {
        Storage::disk('s3')->put('33/conversions/Bachschwöller-web.webp', 'image-bytes');

        $this->get('/media/33/conversions/Bachschw%C3%B6ller-web.webp')->assertOk();
    }

    public function test_second_request_is_served_from_the_local_copy(): void
    {
        Storage::disk('s3')->put('1/a.webp', 'first');
        $this->get('/media/1/a.webp')->assertOk();

        Storage::disk('s3')->delete('1/a.webp');

        $this->get('/media/1/a.webp')->assertOk();
    }

    public function test_missing_file_answers_404_and_leaves_nothing_behind(): void
    {
        $this->get('/media/1/missing.webp')->assertNotFound();

        $this->assertFileDoesNotExist(public_path('media/1/missing.webp'));
    }

    public function test_other_file_types_are_refused(): void
    {
        Storage::disk('s3')->put('1/secret.php', '<?php');
        Storage::disk('s3')->put('1/data.json', '{}');

        $this->get('/media/1/secret.php')->assertNotFound();
        $this->get('/media/1/data.json')->assertNotFound();
        $this->assertFileDoesNotExist(public_path('media/1/secret.php'));
    }

    public function test_path_traversal_is_refused(): void
    {
        Storage::disk('s3')->put('1/a.webp', 'x');

        $this->get('/media/1/..%2F..%2Fcomposer.webp')->assertNotFound();
        $this->get('/media/../.env')->assertNotFound();
        $this->get('/media/1/%2e%2e/a.webp')->assertNotFound();
    }

    public function test_media_url_setting_moves_the_media_addresses_to_the_own_domain(): void
    {
        config(['filesystems.disks.s3.url' => 'https://www.example.at/media']);
        Storage::forgetDisk('s3');

        $url = Storage::disk('s3')->url('33/conversions/hero-web.webp');

        $this->assertSame('https://www.example.at/media/33/conversions/hero-web.webp', $url);
    }
}
