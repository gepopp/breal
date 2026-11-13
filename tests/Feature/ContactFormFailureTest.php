<?php

namespace Tests\Feature;

use App\Livewire\ContactForm;
use App\Mail\VerificationEmail;
use App\Models\ContactRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use RuntimeException;
use Tests\TestCase;

/**
 * When the storage or the mail service is degraded the form must not hang until the web server answers 504: the
 * visitor gets a message and no half saved request stays behind.
 */
class ContactFormFailureTest extends TestCase
{
    use RefreshDatabase;

    private function filledForm(array $uploads = []): Testable
    {
        return Livewire::test(ContactForm::class)
            ->set('data.subject', 'Anfrage')
            ->set('data.firstname', 'Max')
            ->set('data.lastname', 'Mustermann')
            ->set('data.email', 'max@gmail.com')
            ->set('data.phone', '+43 123 456789')
            ->set('data.message', 'Bitte um Infos.')
            ->set('data.address', 'Testgasse 1, 1010 Wien')
            ->set('data.terms', true)
            ->set('uploads', $uploads);
    }

    private function useLocalPrivateDisk(string $root): void
    {
        config(['filesystems.disks.s3_private' => ['driver' => 'local', 'root' => $root, 'throw' => false]]);
    }

    private function upload(): array
    {
        $path = tempnam(sys_get_temp_dir(), 'upload');
        file_put_contents($path, 'content');

        return ['path' => $path, 'name' => 'plan.txt'];
    }

    public function test_a_failing_mail_service_shows_a_message_and_keeps_no_request(): void
    {
        Mail::shouldReceive('to')->andThrow(new RuntimeException('mail service down'));

        $this->filledForm()
            ->call('submitWithAddress')
            ->assertHasErrors('save')
            ->assertSet('is_sent', false);

        $this->assertSame(0, ContactRequest::withoutGlobalScopes()->count());
    }

    public function test_an_upload_that_cannot_be_stored_shows_a_message_and_keeps_no_request(): void
    {
        Mail::fake();

        // A file where the storage root should be: nothing can be written.
        $blocked = tempnam(sys_get_temp_dir(), 'blocked');
        $this->useLocalPrivateDisk($blocked);

        $this->filledForm([$this->upload()])
            ->call('submitWithAddress')
            ->assertHasErrors('save')
            ->assertSet('is_sent', false);

        $this->assertSame(0, ContactRequest::withoutGlobalScopes()->count());
        Mail::assertNothingQueued();
        Mail::assertNothingSent();
    }

    public function test_a_stored_upload_still_sends_the_verification_mail(): void
    {
        Mail::fake();

        $root = sys_get_temp_dir().DIRECTORY_SEPARATOR.'private-'.uniqid();
        $this->useLocalPrivateDisk($root);

        $this->filledForm([$this->upload()])
            ->call('submitWithAddress')
            ->assertHasNoErrors()
            ->assertSet('is_sent', true);

        $this->assertSame(1, ContactRequest::withoutGlobalScopes()->count());
        $this->assertSame(1, ContactRequest::withoutGlobalScopes()->first()->getMedia('uploads')->count());
        Mail::assertQueued(VerificationEmail::class);
    }

    public function test_the_message_is_shown_in_the_form(): void
    {
        Mail::shouldReceive('to')->andThrow(new RuntimeException('mail service down'));

        $this->filledForm()
            ->call('submitWithAddress')
            ->assertSee(__('contact.form.send_failed'));
    }
}
