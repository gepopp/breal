<?php

namespace App\Services;

use App\Enums\CompaniesEnum;

/**
 * Access to the company data in config/company.php.
 */
class CompanyInfo
{
    /**
     * Data of the company the current page belongs to, or of the given one
     * (a name of CompaniesEnum).
     *
     * @return array{name: string, legal_name: string, email: string, website: string, firmenbuch: string, uid: string, managing_directors: list<string>}
     */
    public static function section(?string $company = null): array
    {
        return config('company.sections.'.($company ?? CompaniesEnum::getByRoute()))
            ?? config('company.sections.'.CompaniesEnum::Hausverwaltung->name);
    }

    /**
     * "Franz-Josefs-Kai 65/Mezzanin, 1010 Wien"
     */
    public static function address(): string
    {
        return config('company.address.street').', '.config('company.address.postal_code').' '.config('company.address.city');
    }

    public static function phone(): string
    {
        return config('company.phone');
    }

    public static function fax(): string
    {
        return config('company.fax');
    }

    /**
     * A tel: link without spaces or separators, e.g. "tel:+4315353619".
     */
    public static function telUri(string $number): string
    {
        $digits = preg_replace('/(?!^\+)[^\d]/', '', trim($number));

        if (str_starts_with($digits, '00')) {
            $digits = '+'.substr($digits, 2);
        }

        return 'tel:'.$digits;
    }

    public static function phoneUri(): string
    {
        return self::telUri(self::phone());
    }

    public static function mailtoUri(string $email): string
    {
        return 'mailto:'.trim($email);
    }

    /**
     * Opening or phone hours as "day label => times", localized.
     *
     * @return array<string, string>
     */
    public static function hours(string $kind): array
    {
        $labels = [
            'mon_thu' => __('footer.hours_mon_thu'),
            'fri' => __('footer.hours_fri'),
        ];

        $lines = [];

        foreach (config($kind === 'phone' ? 'company.phone_hours' : 'company.opening_hours') as $group => $ranges) {
            $lines[$labels[$group] ?? $group] = implode(' '.__('footer.and').' ', array_map(
                fn (string $range): string => str_replace('-', '–', $range),
                $ranges
            ));
        }

        return $lines;
    }
}
