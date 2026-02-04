<?php

namespace App\Settings;

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
        $locale = app()->getLocale();
        $localizedName = "{$name}_{$locale}";

        if (property_exists($this, $localizedName)) {
            // Call parent's __get to load values and return the localized property
            return parent::__get($localizedName);
        }

        // Fall back to the original property name via parent
        return parent::__get($name);
    }
}
