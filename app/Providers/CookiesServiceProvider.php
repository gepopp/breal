<?php

namespace App\Providers;

use Whitecube\LaravelCookieConsent\AnalyticCookiesCategory;
use Whitecube\LaravelCookieConsent\Consent;
use Whitecube\LaravelCookieConsent\Cookie;
use Whitecube\LaravelCookieConsent\CookiesGroup;
use Whitecube\LaravelCookieConsent\CookiesRegistrar;
use Whitecube\LaravelCookieConsent\CookiesServiceProvider as ServiceProvider;
use Whitecube\LaravelCookieConsent\Facades\Cookies;

class CookiesServiceProvider extends ServiceProvider
{
    /**
     * Registers the cookies when the cookie registrar is first used (while the
     * page renders) instead of at boot, because the descriptions are translated
     * on registration and the locale of the request is not known at boot.
     */
    public function register(): void
    {
        $this->app->afterResolving(CookiesRegistrar::class, fn () => $this->registerCookies());
    }

    /**
     * Define the cookies users should be aware of.
     */
    protected function registerCookies(): void
    {
        // Register Laravel's base cookies under the "required" cookies section:
        Cookies::essentials()
            ->session()
            ->csrf();

        $this->registerGoogleAnalytics(
            id: config('cookieconsent.google_analytics.id'),
            anonymizeIp: config('cookieconsent.google_analytics.anonymize_ip')
        );

        Cookies::optional()
            ->name('google_maps')
            ->description(__('cookies.google_maps'))
            ->duration(120);
    }

    /**
     * Registers the Google Analytics 4 cookies and scripts.
     *
     * Replaces the package's google() shorthand, which still lists the
     * obsolete Universal Analytics cookie "_gat".
     */
    protected function registerGoogleAnalytics(string $id, bool $anonymizeIp = true): void
    {
        $key = str_starts_with($id, 'G-') ? substr($id, 2) : $id;
        $anonymize = $anonymizeIp ? 'true' : 'false';

        Cookies::analytics()->group(function (CookiesGroup $group) use ($id, $key, $anonymize): void {
            $group->name(AnalyticCookiesCategory::GOOGLE_ANALYTICS)
                ->cookie(fn (Cookie $cookie) => $cookie->name('_ga')
                    ->duration(2 * 365 * 24 * 60)
                    ->description(__('cookieConsent::cookies.defaults._ga'))
                )
                ->cookie(fn (Cookie $cookie) => $cookie->name('_ga_'.strtoupper($key))
                    ->duration(2 * 365 * 24 * 60)
                    ->description(__('cookieConsent::cookies.defaults._ga_ID'))
                )
                ->cookie(fn (Cookie $cookie) => $cookie->name('_gid')
                    ->duration(24 * 60)
                    ->description(__('cookieConsent::cookies.defaults._gid'))
                )
                ->accepted(fn (Consent $consent) => $consent
                    ->script('<script async src="https://www.googletagmanager.com/gtag/js?id='.$id.'"></script>')
                    ->script(
                        '<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag(\'js\',new Date());gtag(\'config\',\''.$id.'\', {\'anonymize_ip\':'.$anonymize.'});</script>'
                    )
                );
        });
    }
}
