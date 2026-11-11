<?php

namespace Tests\Feature;

use App\Mail\ContactRequestSolvedMail;
use App\Models\ContactRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SolvedMailTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_button_shows_its_label_as_written_and_not_in_lowercase(): void
    {
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

        $html = (new ContactRequestSolvedMail($contactRequest))->render();

        $this->assertStringContainsString('Anfrage als gelöst markieren', $html);
        $this->assertStringNotContainsString('text-transform:lowercase', $html);
    }
}
