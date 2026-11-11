<?php

namespace Tests\Feature;

use App\Models\ContactRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class ContactFlowPagesNotIndexedTest extends TestCase
{
    use RefreshDatabase;

    private const NOINDEX = '<meta name="robots" content="noindex">';

    private function contactRequest(): ContactRequest
    {
        // The "solve" route only finds verified requests that are not solved yet.
        return ContactRequest::create([
            'verified_at' => now(),
            'subject' => 'Anfrage',
            'firstname' => 'Max',
            'lastname' => 'Mustermann',
            'email' => 'max@gmail.com',
            'phone' => '+43 123 456789',
            'message' => 'Hallo',
            'token' => 'the-token',
        ]);
    }

    public function test_confirmation_page_is_noindex(): void
    {
        $contactRequest = $this->contactRequest();

        $this->get(URL::signedRoute('confirm', ['id' => $contactRequest->id, 'token' => 'the-token']))
            ->assertOk()
            ->assertSee(self::NOINDEX, false);
    }

    public function test_solved_page_is_noindex(): void
    {
        $contactRequest = $this->contactRequest();

        $this->get(URL::signedRoute('solve', ['request' => $contactRequest->id, 'token' => 'the-token']))
            ->assertOk()
            ->assertSee(self::NOINDEX, false);
    }

    public function test_confirmation_preview_route_is_noindex(): void
    {
        $this->get('/confirm-test')
            ->assertOk()
            ->assertSee(self::NOINDEX, false)
            ->assertSee('404-man.webp', false)
            ->assertDontSee('<video', false)
            ->assertDontSee('.webm', false);
    }

    public function test_sitemap_lists_none_of_the_contact_flow_pages(): void
    {
        $xml = $this->get('/sitemap.xml')->assertOk()->getContent();

        $this->assertStringNotContainsString('confirm-test', $xml);
        $this->assertStringNotContainsString('/confirmation', $xml);
        $this->assertStringNotContainsString('/solve', $xml);
    }

    public function test_normal_pages_stay_indexable(): void
    {
        $this->get('/')->assertOk()->assertDontSee(self::NOINDEX, false);
    }
}
