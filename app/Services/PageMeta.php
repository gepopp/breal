<?php

namespace App\Services;

use App\Enums\CompaniesEnum;
use Illuminate\Support\Facades\Lang;

/**
 * Titles and descriptions for the public pages.
 *
 * Texts live in the language files as "meta.<page>.title|description"; a
 * section specific text ("meta.makler.<page>...") wins on the Makler and
 * Technik pages. Dynamic pages build their meta from their own content.
 */
class PageMeta
{
    public const TITLE_LIMIT = 60;

    public const DESCRIPTION_LIMIT = 155;

    public static function title(string $page): string
    {
        return self::translate($page, 'title');
    }

    public static function description(string $page): string
    {
        return self::translate($page, 'description');
    }

    /**
     * "be real Verwaltung", "be real Makler" or "be real Technik" for the current route.
     */
    public static function sectionName(): string
    {
        return 'be real '.match (CompaniesEnum::getByRoute()) {
            CompaniesEnum::Makler->name => 'Makler',
            CompaniesEnum::Technik->name => 'Technik',
            default => 'Verwaltung',
        };
    }

    /**
     * File name (in public/) of the 1200x630 share image of the current section.
     */
    public static function socialImage(): string
    {
        return match (CompaniesEnum::getByRoute()) {
            CompaniesEnum::Makler->name => 'og-makler.png',
            CompaniesEnum::Technik->name => 'og-technik.png',
            default => 'og-image.png',
        };
    }

    public static function socialImageAlt(): string
    {
        return __('meta.og.image_alt', ['name' => match (CompaniesEnum::getByRoute()) {
            CompaniesEnum::Makler->name => 'be real Makler',
            CompaniesEnum::Technik->name => 'be real Technik',
            default => 'be real Immobilien',
        }]);
    }

    /**
     * "<name> – <suffix>", or the shortened name alone when the full title would be too long.
     */
    public static function compose(string $name, ?string $suffix = null): string
    {
        $name = self::normalizeTitle($name);
        $full = $name.' – '.($suffix ?? self::sectionName());

        return mb_strlen($full) <= self::TITLE_LIMIT ? $full : self::limit($name, self::TITLE_LIMIT);
    }

    /**
     * Plain text excerpt of HTML content, cut at a word boundary.
     */
    public static function excerpt(?string $html, int $limit = self::DESCRIPTION_LIMIT): string
    {
        $text = html_entity_decode(preg_replace('/<[^>]+>/', ' ', (string) $html), ENT_QUOTES | ENT_HTML5);

        return self::limit(trim(preg_replace('/\s+/u', ' ', $text)), $limit);
    }

    /**
     * Collapses whitespace and tags and turns SHOUTED titles into title case.
     */
    public static function normalizeTitle(string $title): string
    {
        $title = trim(preg_replace('/\s+/u', ' ', preg_replace('/<[^>]+>/', ' ', html_entity_decode($title, ENT_QUOTES | ENT_HTML5))));

        if ($title !== '' && $title === mb_strtoupper($title)) {
            $title = mb_convert_case(mb_strtolower($title), MB_CASE_TITLE);
        }

        return $title;
    }

    private static function limit(string $text, int $limit): string
    {
        if (mb_strlen($text) <= $limit) {
            return $text;
        }

        $cut = mb_substr($text, 0, $limit - 1);
        $lastSpace = mb_strrpos($cut, ' ');

        if ($lastSpace !== false && $lastSpace > $limit * 0.6) {
            $cut = mb_substr($cut, 0, $lastSpace);
        }

        return rtrim($cut, ' ,;:–-.').'…';
    }

    private static function translate(string $page, string $field): string
    {
        $sectionKey = 'meta.'.strtolower(CompaniesEnum::getByRoute()).'.'.$page.'.'.$field;

        return Lang::has($sectionKey) ? __($sectionKey) : __('meta.'.$page.'.'.$field);
    }
}
