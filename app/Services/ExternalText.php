<?php

namespace App\Services;

/**
 * Helpers for texts that come from the property feed. The feed is German only,
 * so on pages in another language those values are marked as German
 * (WCAG 3.1.2) while the labels around them stay in the page language.
 */
class ExternalText
{
    /**
     * The attribute for an element that holds German feed text, empty on German pages.
     */
    public static function langAttribute(): string
    {
        return app()->getLocale() === 'de' ? '' : 'lang="de"';
    }

    /**
     * Translated label for the feed's usage type (WOHNEN, GEWERBE, ...).
     */
    public static function usageLabel(?string $usage): string
    {
        return match (strtoupper((string) $usage)) {
            'WOHNEN' => __('makler.usage_type.wohnen'),
            'GEWERBE' => __('makler.usage_type.gewerbe'),
            default => ucfirst(strtolower((string) $usage)),
        };
    }
}
