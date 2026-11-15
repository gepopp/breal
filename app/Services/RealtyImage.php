<?php

namespace App\Services;

/**
 * Addresses of resized copies of the property photos.
 *
 * The photos come from the JustImmo image service, whose address is signed, so other sizes cannot be requested from
 * there. The own `realty-image` route fetches the photo once, stores a WebP copy per width in public/realty-image and
 * lets the web server deliver it from there afterwards (see RealtyImageController).
 */
class RealtyImage
{
    /**
     * @var list<int>
     */
    public const WIDTHS = [160, 320, 480, 800, 1280];

    private const SOURCE_PREFIX = 'https://storage.justimmo.at/';

    private const SOURCE_PATH_PATTERN = '#^(?:[A-Za-z0-9_-]+/)*[A-Za-z0-9_-]+\.(?:jpe?g|png)$#';

    /**
     * Address of the copy with the given width, or null if the photo is not one of the supported ones.
     */
    public static function url(string $source, int $width): ?string
    {
        if (! in_array($width, self::WIDTHS, true) || ! str_starts_with($source, self::SOURCE_PREFIX)) {
            return null;
        }

        $path = substr($source, strlen(self::SOURCE_PREFIX));

        if (! preg_match(self::SOURCE_PATH_PATTERN, $path)) {
            return null;
        }

        return route('realty-image', ['width' => $width, 'key' => self::encode($path).'.webp']);
    }

    /**
     * @param  list<int>  $widths
     */
    public static function srcset(string $source, array $widths): ?string
    {
        $candidates = [];

        foreach ($widths as $width) {
            $url = self::url($source, $width);

            if ($url === null) {
                return null;
            }

            $candidates[] = $url.' '.$width.'w';
        }

        return implode(', ', $candidates);
    }

    /**
     * The photo address behind a `key` of the route, or null if it is not a supported one.
     */
    public static function sourceFromKey(string $key): ?string
    {
        if (! str_ends_with($key, '.webp')) {
            return null;
        }

        $path = self::decode(substr($key, 0, -5));

        if ($path === null || ! preg_match(self::SOURCE_PATH_PATTERN, $path)) {
            return null;
        }

        return self::SOURCE_PREFIX.$path;
    }

    private static function encode(string $path): string
    {
        return rtrim(strtr(base64_encode($path), '+/', '-_'), '=');
    }

    private static function decode(string $key): ?string
    {
        $decoded = base64_decode(strtr($key, '-_', '+/'), true);

        return $decoded === false ? null : $decoded;
    }
}
