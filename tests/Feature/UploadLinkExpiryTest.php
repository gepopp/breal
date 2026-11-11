<?php

namespace Tests\Feature;

use App\Mail\ContactRequestConfirmedMail;
use App\Mail\NewContactRequestAdminMail;
use App\Models\ContactRequest;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Tests\TestCase;

class UploadLinkExpiryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * An S3 disk that only signs URLs (no request is sent), like the private bucket.
     */
    private function s3Disk(): Filesystem
    {
        return Storage::build([
            'driver' => 's3',
            'key' => 'test-key',
            'secret' => 'test-secret',
            'region' => 'eu-central',
            'bucket' => 'test-bucket',
            'endpoint' => 'https://s3.example.test',
            'use_path_style_endpoint' => true,
        ]);
    }

    public function test_upload_links_expire_within_the_s3_limit_of_one_week(): void
    {
        $seconds = ContactRequest::uploadLinkExpiresAt()->getTimestamp() - now()->getTimestamp();

        $this->assertLessThan(7 * 24 * 3600, $seconds);
        $this->assertGreaterThan(5 * 24 * 3600, $seconds);
    }

    public function test_the_expiry_can_be_signed_for_the_private_bucket(): void
    {
        $url = $this->s3Disk()->temporaryUrl('uploads/photo.jpg', ContactRequest::uploadLinkExpiresAt());

        $this->assertStringContainsString('X-Amz-Signature=', $url);
    }

    /**
     * @return array<string, array{0: class-string<Mailable>}>
     */
    public static function mailsWithUploadLinks(): array
    {
        return [
            'office mail' => [NewContactRequestAdminMail::class],
            'confirmation mail' => [ContactRequestConfirmedMail::class],
        ];
    }

    /**
     * @param  class-string<Mailable>  $mailClass
     */
    #[DataProvider('mailsWithUploadLinks')]
    public function test_mails_with_an_upload_contain_a_valid_download_link(string $mailClass): void
    {
        config(['filesystems.disks.s3_private' => [
            'driver' => 's3',
            'key' => 'test-key',
            'secret' => 'test-secret',
            'region' => 'eu-central',
            'bucket' => 'test-bucket',
            'endpoint' => 'https://s3.example.test',
            'use_path_style_endpoint' => true,
        ]]);

        $contactRequest = ContactRequest::create([
            'subject' => 'Anfrage',
            'firstname' => 'Max',
            'lastname' => 'Mustermann',
            'email' => 'max@gmail.com',
            'phone' => '+43 123 456789',
            'message' => 'Hallo',
            'token' => 'the-token',
            'verified_at' => now(),
        ]);

        Media::create([
            'model_type' => ContactRequest::class,
            'model_id' => $contactRequest->id,
            'uuid' => (string) Str::uuid(),
            'collection_name' => 'uploads',
            'name' => 'foto',
            'file_name' => 'foto.jpg',
            'mime_type' => 'image/jpeg',
            'disk' => 's3_private',
            'conversions_disk' => 's3_private',
            'size' => 1000,
            'manipulations' => [],
            'custom_properties' => [],
            'generated_conversions' => [],
            'responsive_images' => [],
        ]);

        $html = (new $mailClass($contactRequest->fresh()))->render();

        preg_match('/X-Amz-Expires=(\d+)/', $html, $matches);

        $this->assertStringContainsString('X-Amz-Signature=', $html);
        $this->assertNotEmpty($matches, 'The download link of the upload is missing in the mail');
        $this->assertLessThan(7 * 24 * 3600, (int) $matches[1]);
    }

    public function test_s3_refuses_links_that_are_valid_for_more_than_a_week(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->s3Disk()->temporaryUrl('uploads/photo.jpg', now()->addDays(7)->addSecond());
    }
}
