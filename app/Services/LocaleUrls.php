<?php

namespace App\Services;

use App\Http\Middleware\ApplyLocalePrefix;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class LocaleUrls
{
    public function __construct(private readonly Request $request) {}

    /**
     * Whether the route is a public page that exists in German and English.
     */
    public static function isLocalizedRoute(?Route $route): bool
    {
        return $route?->getAction('localized') === true;
    }

    /**
     * Prefixes a path with the language prefix of the current locale.
     */
    public static function prefixPath(string $path, string $locale): string
    {
        if ($locale !== ApplyLocalePrefix::PREFIXED_LOCALE) {
            return $path;
        }

        return rtrim('/'.$locale.$path, '/');
    }

    public function isLocalized(): bool
    {
        return self::isLocalizedRoute($this->request->route());
    }

    /**
     * Absolute URL of the current page in the given locale.
     */
    public function forLocale(string $locale): string
    {
        return rtrim($this->request->getSchemeAndHttpHost().self::prefixPath($this->pagePath(), $locale), '/');
    }

    public function current(): string
    {
        return $this->forLocale(app()->getLocale());
    }

    /**
     * Unprefixed path of the page being viewed. Livewire update requests post
     * to a generic endpoint, so the page path is taken from the referer there.
     */
    private function pagePath(): string
    {
        $referer = $this->request->headers->get('referer');

        if ($this->request->headers->has('X-Livewire') && $referer !== null) {
            $path = (string) parse_url($referer, PHP_URL_PATH);

            return preg_replace('#^/'.ApplyLocalePrefix::PREFIXED_LOCALE.'(?=/|$)#', '', $path) ?: '/';
        }

        return $this->request->getPathInfo();
    }

    /**
     * @return array<string, string> hreflang value => absolute URL
     */
    public function alternates(): array
    {
        return [
            'de-AT' => $this->forLocale('de'),
            'en' => $this->forLocale('en'),
            'x-default' => $this->forLocale('de'),
        ];
    }
}
