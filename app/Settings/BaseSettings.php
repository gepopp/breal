<?php

namespace App\Settings;

use App\Services\HtmlLinks;
use Spatie\LaravelSettings\Settings;

abstract class BaseSettings extends Settings
{
    /**
     * Retrieves a setting value with automatic locale-based fallback.
     *
     * If a setting is not found, it will attempt to find a setting
     * with the same name suffixed with the current app locale.
     *
     * Example: If 'hero_header' is not found, it will try 'hero_header_de' (if locale is 'de').
     */
    public function __get($name): mixed
    {
        // Check if a locale-suffixed version exists first
        $locale = app()->getLocale() ?: config('app.fallback_locale', 'de');
        $localizedName = "{$name}_{$locale}";

        if (property_exists($this, $localizedName)) {
            // Call parent's __get to load values and return the localized property
            return $this->normalized(parent::__get($localizedName));
        }

        if (! property_exists($this, $name)) {
            return '';
        }

        // Fall back to the original property name via parent
        return $this->normalized(parent::__get($name));
    }

    /**
     * Repairs contact links and the company address in texts entered in the admin panel.
     */
    private function normalized(mixed $value): mixed
    {
        return is_string($value) ? HtmlLinks::normalize($value) : $value;
    }
}
