<?php

namespace Tests\Feature;

use App\Livewire\ContactForm;
use App\Mail\NewContactRequestAdminMail;
use App\Models\ContactRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ContactRequestCompanyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The company the contact form on a page has been rendered with.
     */
    private function formCompanyOn(string $path): ?string
    {
        $html = html_entity_decode($this->get($path)->assertOk()->getContent());

        preg_match_all('/wire:snapshot="(\{.*?\})" wire:effects/s', $html, $matches);

        foreach ($matches[1] as $snapshot) {
            $decoded = json_decode($snapshot, true);

            if (($decoded['memo']['name'] ?? null) === 'contact-form') {
                return $decoded['data']['company'] ?? null;
            }
        }

        return null;
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function pages(): array
    {
        return [
            'Hausverwaltung' => ['/kontakt', 'Hausverwaltung'],
            'Makler' => ['/makler/kontakt', 'Makler'],
            'Technik' => ['/technik/kontakt', 'Technik'],
        ];
    }

    #[DataProvider('pages')]
    public function test_contact_form_knows_the_area_of_its_page(string $path, string $company): void
    {
        $this->assertSame($company, $this->formCompanyOn($path));
    }

    private function submit(string $company): void
    {
        Mail::fake();

        Livewire::test(ContactForm::class)
            ->set('company', $company)
            ->set('address', false)
            ->set('data.subject', 'Frage')
            ->set('data.firstname', 'Max')
            ->set('data.lastname', 'Muster')
            ->set('data.email', 'max@gmail.com')
            ->set('data.phone', '+43 123 456789')
            ->set('data.message', 'Hallo')
            ->set('data.terms', true)
            ->call('submitWithoutAddress')
            ->assertHasNoErrors();
    }

    public function test_contact_request_records_the_area_it_was_sent_from(): void
    {
        $this->submit('Makler');

        $this->assertDatabaseHas('contact_requests', ['email' => 'max@gmail.com', 'company' => 'Makler']);
    }

    public function test_unknown_area_values_fall_back_to_hausverwaltung(): void
    {
        $this->submit('Irgendwas');

        $this->assertDatabaseHas('contact_requests', ['email' => 'max@gmail.com', 'company' => 'Hausverwaltung']);
    }

    public function test_admin_mail_names_the_area(): void
    {
        $request = ContactRequest::withoutGlobalScopes()->create([
            'token' => 't', 'firstname' => 'Max', 'lastname' => 'Muster', 'email' => 'max@gmail.com',
            'phone' => '+43 1', 'message' => 'Hallo', 'company' => 'Technik',
        ]);

        (new NewContactRequestAdminMail($request))->assertSeeInHtml('Bereich:', false);
        (new NewContactRequestAdminMail($request))->assertSeeInHtml('Technik', false);
    }
}
