<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Mechanisms\HandleRequests\HandleRequests;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LazyContactFormTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Finds the lazily loaded contact form on a page and loads it the way the browser does.
     *
     * @return array{html: string, snapshot: array<string, mixed>}
     */
    private function loadLazyContactForm(string $path): array
    {
        $page = html_entity_decode($this->get($path)->assertOk()->getContent());

        preg_match_all('/wire:snapshot="(\{.*?\})" wire:effects/s', $page, $snapshots);
        $placeholder = collect($snapshots[1])
            ->map(fn (string $json): array => json_decode($json, true))
            ->first(fn (array $snapshot): bool => ($snapshot['memo']['name'] ?? null) === 'contact-form');

        $this->assertNotNull($placeholder, 'The contact form is not on the page');
        $this->assertMatchesRegularExpression('/x-init="\$wire\.__lazyLoad\(/', $page, 'The contact form is not loaded lazily');

        preg_match('/__lazyLoad\(\'([^\']+)\'\)/', $page, $encoded);

        $response = $this->withHeaders(['X-Livewire' => 'true'])->postJson(app(HandleRequests::class)->getUpdateUri(), [
            'components' => [[
                'snapshot' => json_encode($placeholder),
                'updates' => [],
                'calls' => [['path' => '', 'method' => '__lazyLoad', 'params' => [$encoded[1]]]],
            ]],
        ])->assertOk();

        $component = $response->json('components.0');

        return [
            'html' => html_entity_decode($component['effects']['html'] ?? ''),
            'snapshot' => json_decode($component['snapshot'], true),
        ];
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function pages(): array
    {
        return [
            'Hausverwaltung' => ['/', 'Hausverwaltung'],
            'Makler' => ['/makler', 'Makler'],
            'Technik' => ['/technik', 'Technik'],
        ];
    }

    #[DataProvider('pages')]
    public function test_the_lazily_loaded_form_keeps_the_area_of_the_page_it_is_on(string $path, string $company): void
    {
        $loaded = $this->loadLazyContactForm($path);

        $this->assertSame($company, $loaded['snapshot']['data']['company']);
        $this->assertStringContainsString('wire:submit', $loaded['html']);
    }

    public function test_the_lazily_loaded_form_speaks_the_language_of_the_page(): void
    {
        $german = $this->loadLazyContactForm('/')['html'];
        $english = $this->loadLazyContactForm('/en')['html'];

        $this->assertStringContainsString(__('contact.form.firstname', [], 'de'), $german);
        $this->assertStringContainsString(__('contact.form.firstname', [], 'en'), $english);
        $this->assertStringNotContainsString(__('contact.form.firstname', [], 'de'), $english);
    }
}
