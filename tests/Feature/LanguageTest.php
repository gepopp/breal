<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LanguageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, array{0: string}>
     */
    public static function publicPages(): array
    {
        return [
            'start' => ['/'],
            'makler' => ['/makler/start'],
            'technik' => ['/technik/start'],
            'kontakt' => ['/kontakt'],
            'faq' => ['/faq'],
            'impressum' => ['/impressum'],
            'datenschutz' => ['/datenschutz'],
        ];
    }

    public function test_pages_are_german_without_accept_language_header(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('<html lang="de-AT">', false);
        $response->assertSee(__('meta.start.title'));
        $response->assertDontSee('meta.start.title');
    }

    public function test_english_accept_language_header_does_not_switch_the_language(): void
    {
        $response = $this->withHeaders(['Accept-Language' => 'en-US,en;q=0.9'])->get('/');

        $response->assertOk();
        $response->assertSee('<html lang="de-AT">', false);
        $response->assertSee(__('meta.start.title'));
    }

    public function test_default_locale_is_german(): void
    {
        $this->assertSame('de', config('app.locale'));
        $this->assertFalse(config('language.auto'));
    }

    #[DataProvider('publicPages')]
    public function test_public_pages_do_not_expose_translation_keys(string $uri): void
    {
        $response = $this->get($uri);

        $response->assertOk();

        $html = preg_replace('#<script\b.*?</script>#si', '', $response->getContent());
        $text = html_entity_decode(strip_tags($html));

        foreach ($this->dottedTranslationKeys() as $key) {
            $this->assertStringNotContainsString($key, $text, "Raw translation key [{$key}] is visible on {$uri}.");
        }
    }

    public function test_cookie_banner_is_german_and_lists_no_obsolete_cookies(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Alles akzeptieren');
        $response->assertSee('Dieses Cookie muss zum Anzeigen von Google Maps aktiviert werden.');
        $response->assertDontSee('Accept all');
        $response->assertDontSee('We use cookies');
        $response->assertDontSee('_gat');
        $response->assertDontSee('benötigen sie');
        $response->assertSee('_ga');
    }

    /**
     * @return list<string>
     */
    private function dottedTranslationKeys(): array
    {
        $translations = json_decode(file_get_contents(lang_path('de.json')), true, flags: JSON_THROW_ON_ERROR);

        return array_values(array_filter(
            array_keys($translations),
            fn (string $key): bool => preg_match('/^[a-z_]+(\.[a-z_]+)+$/', $key) === 1
        ));
    }
}
