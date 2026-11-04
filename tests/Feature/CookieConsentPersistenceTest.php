<?php

namespace Tests\Feature;

use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Facade;
use Symfony\Component\HttpFoundation\Cookie;
use Tests\TestCase;
use Whitecube\LaravelCookieConsent\CookiesManager;
use Whitecube\LaravelCookieConsent\CookiesRegistrar;

class CookieConsentPersistenceTest extends TestCase
{
    use RefreshDatabase;

    private const BANNER_MARKER = 'cookie-consent/accept-all';

    private function consentCookie(string $route): Cookie
    {
        $response = $this->post(route($route));

        $cookie = collect($response->headers->getCookies())
            ->first(fn (Cookie $cookie): bool => $cookie->getName() === config('cookieconsent.cookie.name'));

        $this->assertNotNull($cookie, 'The consent route did not set the consent cookie.');

        return $cookie;
    }

    /**
     * Every browser request boots the application anew; within one test the consent
     * manager would otherwise keep the (cookie-less) state of the previous request.
     */
    private function startNewRequest(): void
    {
        Facade::clearResolvedInstances();
        $this->app->forgetInstance(CookiesManager::class);
        $this->app->forgetInstance(CookiesRegistrar::class);
    }

    public function test_consent_cookie_name_from_the_config_is_excluded_from_encryption(): void
    {
        $this->assertTrue(app(EncryptCookies::class)->isDisabled(config('cookieconsent.cookie.name')));
    }

    public function test_banner_is_shown_without_consent(): void
    {
        $this->get('/')->assertSee(self::BANNER_MARKER, false);
    }

    public function test_banner_stays_away_after_accepting_all_cookies(): void
    {
        $cookie = $this->consentCookie('cookieconsent.accept.all');
        $this->startNewRequest();

        // The browser sends the cookie back exactly as the server set it.
        $this->withUnencryptedCookie($cookie->getName(), $cookie->getValue())
            ->get('/')
            ->assertDontSee(self::BANNER_MARKER, false);
    }

    public function test_banner_stays_away_after_accepting_essential_cookies_only(): void
    {
        $cookie = $this->consentCookie('cookieconsent.accept.essentials');
        $this->startNewRequest();

        $this->withUnencryptedCookie($cookie->getName(), $cookie->getValue())
            ->get('/team')
            ->assertDontSee(self::BANNER_MARKER, false);
    }
}
