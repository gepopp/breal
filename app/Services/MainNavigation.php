<?php

namespace App\Services;

use App\Models\Realty;
use Illuminate\Support\Facades\Route;

/**
 * The links of the main navigation, shared by the desktop bar and the mobile menu so both always offer the same pages.
 */
class MainNavigation
{
    /**
     * @return array{company: string, items: list<array{route: string, label: string, active: string, desktop: bool, mobile: bool, dropdown: ?string}>}
     */
    public static function forCurrentPage(): array
    {
        $company = match (true) {
            Route::is('makler.*') => 'makler',
            Route::is('technik.*') => 'technik',
            default => 'hausverwaltung',
        };

        return ['company' => $company, 'items' => self::items($company)];
    }

    /**
     * @return list<array{route: string, label: string, active: string, desktop: bool, mobile: bool, dropdown: ?string}>
     */
    public static function items(string $company): array
    {
        return match ($company) {
            'makler' => array_values(array_filter([
                Realty::count() ? self::item('makler.immobiliensuche', __('navigation.property_search')) : null,
                self::item('makler.karriere', __('navigation.careers')),
                self::item('makler.kontakt', __('navigation.contact'), desktop: false),
                self::item('makler.team', __('navigation.team')),
            ])),
            'technik' => [
                self::item('technik.leistungen', __('navigation.services'), mobile: false),
                self::item('technik.karriere', __('navigation.careers')),
                self::item('technik.kontakt', __('navigation.contact'), desktop: false),
                self::item('technik.team', __('navigation.team')),
            ],
            default => [
                self::item('hausverwaltung.leistungen', __('navigation.services'), dropdown: 'parts.competence-dropdown'),
                self::item('hausverwaltung.service', __('navigation.service'), dropdown: 'parts.menu-service-dropdown'),
                self::item('hausverwaltung.karriere', __('navigation.careers')),
                self::item('hausverwaltung.kontakt', __('navigation.contact'), desktop: false),
                self::item('hausverwaltung.team', __('navigation.team')),
                self::item('hausverwaltung.faq', 'FAQ'),
                self::item('verwalterservice', __('Corporate Service'), desktop: false),
            ],
        };
    }

    /**
     * @return array{route: string, label: string, active: string, desktop: bool, mobile: bool, dropdown: ?string}
     */
    private static function item(string $route, string $label, bool $desktop = true, bool $mobile = true, ?string $dropdown = null): array
    {
        return [
            'route' => $route,
            'label' => $label,
            'active' => $route,
            'desktop' => $desktop,
            'mobile' => $mobile,
            'dropdown' => $dropdown,
        ];
    }
}
