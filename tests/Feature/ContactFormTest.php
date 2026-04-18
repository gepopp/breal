<?php

namespace Tests\Feature;

use App\Livewire\ContactForm;
use App\Mail\ContactRequestConfirmedMail;
use App\Mail\NewContactRequestAdminMail;
use App\Mail\VerificationEmail;
use App\Models\ContactRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_submission_with_address_creates_contact_request_and_sends_verification_email(): void
    {
        Mail::fake();

        Livewire::test(ContactForm::class)
            ->set('data.subject', 'Anfrage Wohnung')
            ->set('data.firstname', 'Max')
            ->set('data.lastname', 'Mustermann')
            ->set('data.email', 'max@gmail.com')
            ->set('data.phone', '+43 123 456789')
            ->set('data.message', 'Bitte um Infos.')
            ->set('data.address', 'Testgasse 1, 1010 Wien')
            ->set('data.terms', true)
            ->call('submitWithAddress')
            ->assertHasNoErrors()
            ->assertSet('is_sent', true);

        $this->assertDatabaseHas('contact_requests', [
            'email' => 'max@gmail.com',
            'address' => 'Testgasse 1, 1010 Wien',
        ]);

        Mail::assertQueued(
            VerificationEmail::class,
            fn (VerificationEmail $mail) => $mail->hasTo('max@gmail.com')
        );
    }

    public function test_submission_without_address_is_allowed_when_disabled(): void
    {
        Mail::fake();

        Livewire::test(ContactForm::class)
            ->set('address', false)
            ->set('data.subject', 'Anfrage')
            ->set('data.firstname', 'Max')
            ->set('data.lastname', 'Mustermann')
            ->set('data.email', 'max@gmail.com')
            ->set('data.phone', '+43 123 456789')
            ->set('data.message', 'Text.')
            ->set('data.terms', true)
            ->call('submitWithoutAddress')
            ->assertHasNoErrors()
            ->assertSet('is_sent', true);

        $this->assertDatabaseHas('contact_requests', [
            'email' => 'max@gmail.com',
            'address' => null,
        ]);
    }

    public function test_address_is_required_when_submitting_with_address(): void
    {
        Mail::fake();

        Livewire::test(ContactForm::class)
            ->set('data.subject', 'Anfrage')
            ->set('data.firstname', 'Max')
            ->set('data.lastname', 'Mustermann')
            ->set('data.email', 'max@gmail.com')
            ->set('data.phone', '+43 123 456789')
            ->set('data.message', 'Text.')
            ->set('data.address', null)
            ->set('data.terms', true)
            ->call('submitWithAddress')
            ->assertHasErrors(['data.address' => 'required']);

        $this->assertDatabaseCount('contact_requests', 0);
        Mail::assertNothingQueued();
        Mail::assertNothingSent();
    }

    public function test_confirmation_route_dispatches_admin_and_user_mails_with_contact_request(): void
    {
        Mail::fake();

        $contactRequest = ContactRequest::create([
            'subject' => 'Anfrage',
            'firstname' => 'Max',
            'lastname' => 'Mustermann',
            'email' => 'max@gmail.com',
            'phone' => '+43 123 456789',
            'message' => 'Hallo',
            'address' => 'Testgasse 1, 1010 Wien',
            'token' => 'the-token',
        ]);

        $url = URL::signedRoute('confirm', [
            'id' => $contactRequest->id,
            'token' => 'the-token',
        ]);

        $this->get($url)->assertOk();

        $this->assertNotNull(
            ContactRequest::withoutGlobalScopes()->find($contactRequest->id)->verified_at
        );

        Mail::assertSent(
            NewContactRequestAdminMail::class,
            fn (NewContactRequestAdminMail $mail) => $mail->contactRequest->is($contactRequest)
                && $mail->contactRequest->address === 'Testgasse 1, 1010 Wien'
                && $mail->hasTo(ContactForm::RECIPIENT)
        );

        Mail::assertQueued(
            ContactRequestConfirmedMail::class,
            fn (ContactRequestConfirmedMail $mail) => $mail->contactRequest->is($contactRequest)
                && $mail->hasTo('max@gmail.com')
        );
    }

    public function test_confirmation_route_rejects_invalid_token(): void
    {
        Mail::fake();

        $contactRequest = ContactRequest::create([
            'subject' => 'Anfrage',
            'firstname' => 'Max',
            'lastname' => 'Mustermann',
            'email' => 'max@gmail.com',
            'phone' => '+43 123 456789',
            'message' => 'Hallo',
            'token' => 'the-token',
        ]);

        $url = URL::signedRoute('confirm', [
            'id' => $contactRequest->id,
            'token' => 'wrong-token',
        ]);

        $this->get($url)->assertStatus(401);

        Mail::assertNothingQueued();
        Mail::assertNothingSent();
    }
}
