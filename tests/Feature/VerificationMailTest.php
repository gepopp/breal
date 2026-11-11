<?php

namespace Tests\Feature;

use App\Mail\VerificationEmail;
use App\Models\ContactRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class VerificationMailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function languages(): array
    {
        return [
            'german' => ['de', 'Anmeldung abschließen'],
            'english' => ['en', 'Complete registration'],
        ];
    }

    #[DataProvider('languages')]
    public function test_the_button_shows_its_label_as_written_and_not_in_lowercase(string $locale, string $label): void
    {
        $contactRequest = ContactRequest::create([
            'subject' => 'Anfrage',
            'firstname' => 'Max',
            'lastname' => 'Mustermann',
            'email' => 'max@gmail.com',
            'phone' => '+43 123 456789',
            'message' => 'Hallo',
            'token' => 'the-token',
            'locale' => $locale,
        ]);

        $html = (new VerificationEmail($contactRequest))->render();

        $this->assertStringContainsString($label, $html);
        $this->assertStringNotContainsString('text-transform:lowercase', $html);
    }
}
