<?php

namespace Tests\Feature;

use App\Livewire\ContactForm;
use App\Mail\ContactRequestConfirmedMail;
use App\Mail\VerificationEmail;
use App\Models\ContactRequest;
use Database\Factories\QuestionFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;
use Tests\TestCase;

class EnglishContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_cookie_banner_is_english_on_english_pages(): void
    {
        $this->get('/en')
            ->assertOk()
            ->assertSee('Main cookie used by Google Analytics', false)
            ->assertSee('This cookie must be enabled to display Google Maps.')
            ->assertDontSee('Haupt-Cookie, das von Google Analytics')
            ->assertDontSee('Dieses Cookie muss zum Anzeigen von Google Maps');
    }

    public function test_cookie_banner_stays_german_on_german_pages(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Haupt-Cookie, das von Google Analytics', false)
            ->assertSee('Dieses Cookie muss zum Anzeigen von Google Maps aktiviert werden.')
            ->assertDontSee('Main cookie used by Google Analytics');
    }

    public function test_service_page_headings_and_intro_follow_the_language(): void
    {
        $this->get('/en/service')->assertOk()->assertSee('Our Promise')->assertSee('Often things need to be quick and easy')->assertDontSee('Unser Versprechen');
        $this->get('/service')->assertOk()->assertSee('Unser Versprechen')->assertDontSee('Our Promise');
    }

    public function test_contact_headings_follow_the_language(): void
    {
        $this->get('/en/kontakt')->assertOk()->assertSee('Say Hello to Us!')->assertSee('Your Contact Persons!')->assertDontSee('Sagen Sie uns Hallo!');
        $this->get('/kontakt')->assertOk()->assertSee('Sagen Sie uns Hallo!')->assertSee('Ihre Ansprechpartner!')->assertDontSee('Say Hello to Us!');
    }

    public function test_privacy_policy_and_accessibility_statement_exist_in_english(): void
    {
        $this->get('/en/datenschutz')->assertOk()->assertSee('Data Protection at a Glance')->assertDontSee('Datenschutz auf einen Blick');
        $this->get('/datenschutz')->assertOk()->assertSee('Datenschutz auf einen Blick')->assertDontSee('Data Protection at a Glance');
        $this->get('/en/barrierefreiheit')->assertOk()->assertSee('strives to make its website');
    }

    public function test_faq_list_uses_english_texts_and_falls_back_to_german_where_missing(): void
    {
        $translated = QuestionFactory::new()->create(['question' => 'Deutsche Frage Eins', 'answer' => '<p>Deutsche Antwort Eins</p>']);
        $translated->setTranslation('question', 'en', 'English question one')->setTranslation('answer', 'en', '<p>English answer one</p>')->save();
        QuestionFactory::new()->create(['question' => 'Nur deutsche Frage', 'answer' => '<p>Nur deutsche Antwort</p>']);

        $this->get('/en/faq')
            ->assertOk()
            ->assertSee('English question one')
            ->assertSee('English answer one', false)
            ->assertDontSee('Deutsche Frage Eins')
            ->assertSee('Nur deutsche Frage')
            ->assertSee('Do you have questions or suggestions on this topic?')
            ->assertSee('Contact us')
            ->assertDontSee('kontaktieren sie uns');

        $this->get('/faq')
            ->assertOk()
            ->assertSee('Deutsche Frage Eins')
            ->assertSee('Sie haben Fragen oder Anregungen zu diesem Thema?')
            ->assertDontSee('English question one');
    }

    public function test_faq_structured_data_follows_the_page_language(): void
    {
        $question = QuestionFactory::new()->create(['question' => 'Deutsche Frage Zwei', 'answer' => '<p>Deutsche Antwort Zwei</p>']);
        $question->setTranslation('question', 'en', 'English question two')->setTranslation('answer', 'en', '<p>English answer two</p>')->save();

        $html = $this->get('/en/faq')->assertOk()->getContent();

        $this->assertStringContainsString('English question two', $html);
        $this->assertStringContainsString('English answer two', $html);
        $this->assertStringNotContainsString('Deutsche Antwort Zwei', $html);
    }

    public function test_contact_form_confirmation_is_translated(): void
    {
        app()->setLocale('en');

        $this->blade('<x-contact-form-sent />')
            ->assertSee('Thank you!')
            ->assertSee('please confirm your e-mail address')
            ->assertDontSee('Vielen Dank!');
    }

    public function test_not_found_page_is_translated(): void
    {
        $this->get('/en/gibt-es-nicht')
            ->assertNotFound()
            ->assertSee('Unfortunately, we could not find this content')
            ->assertSee('Please check the address in your browser for typos')
            ->assertDontSee('Diesen Inhalt konnten wir leider nicht finden');
    }

    public function test_contact_request_remembers_the_language_of_the_form(): void
    {
        Mail::fake();
        app()->setLocale('en');

        Livewire::test(ContactForm::class)
            ->set('address', false)
            ->set('data.subject', 'Question')
            ->set('data.firstname', 'Jane')
            ->set('data.lastname', 'Doe')
            ->set('data.email', 'jane@gmail.com')
            ->set('data.phone', '+43 123 456789')
            ->set('data.message', 'Hello')
            ->set('data.terms', true)
            ->call('submitWithoutAddress')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('contact_requests', ['email' => 'jane@gmail.com', 'locale' => 'en']);
        Mail::assertQueued(VerificationEmail::class, fn (VerificationEmail $mail): bool => $mail->locale === 'en');
    }

    public function test_german_contact_request_is_stored_as_german(): void
    {
        Mail::fake();

        Livewire::test(ContactForm::class)
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

        $this->assertDatabaseHas('contact_requests', ['email' => 'max@gmail.com', 'locale' => 'de']);
    }

    public function test_verification_and_confirmation_mails_are_sent_in_the_language_of_the_request(): void
    {
        $request = ContactRequest::withoutGlobalScopes()->create([
            'token' => 'token-en', 'firstname' => 'Jane', 'lastname' => 'Doe', 'email' => 'jane@gmail.com',
            'phone' => '+43 1', 'message' => 'Hello', 'company' => 'Hausverwaltung', 'locale' => 'en',
        ]);

        $verification = new VerificationEmail($request);
        $verification->assertSeeInHtml('Complete registration', false);
        $verification->assertSeeInHtml('This link is valid for 60 minutes.', false);
        $verification->assertDontSeeInHtml('Anmeldung abschließen', false);

        $confirmed = new ContactRequestConfirmedMail($request);
        $confirmed->assertSeeInHtml('your request!', false);
        $confirmed->assertSeeInHtml('With motivated regards', false);
        $confirmed->assertDontSeeInHtml('Herzlichen Dank', false);

        $verification->assertHasSubject('Confirm your e-mail address');
        $confirmed->assertHasSubject('Your request to be real');
    }

    public function test_german_mails_stay_german(): void
    {
        $request = ContactRequest::withoutGlobalScopes()->create([
            'token' => 'token-de', 'firstname' => 'Max', 'lastname' => 'Muster', 'email' => 'max@gmail.com',
            'phone' => '+43 1', 'message' => 'Hallo', 'company' => 'Hausverwaltung',
        ]);

        $verification = new VerificationEmail($request);
        $verification->assertSeeInHtml('Anmeldung abschließen', false);
        $verification->assertHasSubject('Bestätigen Sie Ihre E-Mail-Adresse');
        (new ContactRequestConfirmedMail($request))->assertSeeInHtml('Herzlichen Dank für', false);
    }

    public function test_confirmation_page_after_the_e_mail_link_uses_the_language_of_the_request(): void
    {
        Mail::fake();

        $request = ContactRequest::withoutGlobalScopes()->create([
            'token' => 'token-en', 'firstname' => 'Jane', 'lastname' => 'Doe', 'email' => 'jane@gmail.com',
            'phone' => '+43 1', 'message' => 'Hello', 'company' => 'Hausverwaltung', 'locale' => 'en',
        ]);

        $this->get(URL::signedRoute('confirm', ['id' => $request->id, 'token' => 'token-en']))
            ->assertOk()
            ->assertSee('Your email address is now confirmed')
            ->assertDontSee('Ihre E-Mail-Adresse ist nun bestätigt');

        Mail::assertQueued(ContactRequestConfirmedMail::class, fn (ContactRequestConfirmedMail $mail): bool => $mail->locale === 'en');
    }

    public function test_confirmation_link_for_a_missing_request_is_rejected(): void
    {
        $this->get(URL::signedRoute('confirm', ['id' => 999, 'token' => 'x']))->assertUnauthorized();
    }

    public function test_every_translation_key_of_the_public_site_has_an_english_text(): void
    {
        $english = json_decode(File::get(lang_path('en.json')), true, flags: JSON_THROW_ON_ERROR);

        // Views of the leftover Laravel starter kit are not part of the public site.
        $starterKit = ['settings/', 'layouts/app/', 'errors/', 'dashboard.blade', 'app-logo', 'settings-heading', 'action-message', 'vendor/mail', 'livewire-dropzone'];

        $missing = [];

        foreach (File::allFiles(resource_path('views')) as $file) {
            $path = str_replace('\\', '/', $file->getPathname());

            if (collect($starterKit)->contains(fn (string $skip): bool => str_contains($path, $skip))) {
                continue;
            }

            preg_match_all('/(?:__|trans|@lang)\(\s*\'((?:[^\'\\\\]|\\\\.)+)\'/', $file->getContents(), $matches);

            foreach ($matches[1] as $key) {
                $key = stripslashes($key);

                if (! array_key_exists($key, $english) && ! str_contains($key, '::') && ! str_ends_with($key, '.')) {
                    $missing[$key] = basename($path);
                }
            }
        }

        $this->assertSame([], $missing, 'Keys without English text in lang/en.json: '.json_encode($missing, JSON_UNESCAPED_UNICODE));
    }
}
