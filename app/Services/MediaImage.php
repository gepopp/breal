<?php

namespace App\Services;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\ResponsiveImages\ResponsiveImage;

/**
 * Picks the files of a media item that the public pages should load.
 *
 * Models register a `web` conversion (WebP, responsive). Until it has been
 * generated for an existing upload (`media-library:regenerate`), the responsive
 * variants of the original are used, capped to a sensible width so that the
 * multi-megabyte camera original is never offered to the browser.
 */
class MediaImage
{
    public const CONVERSION = 'web';

    public const MAX_WIDTH = 1920;

    /**
     * @return array{src: string, srcset: ?string, width: ?int, height: ?int}
     */
    public static function attributes(Media $media): array
    {
        $conversion = $media->hasGeneratedConversion(self::CONVERSION) ? self::CONVERSION : '';

        $files = $media->responsiveImages($conversion)->files
            ->filter(fn (ResponsiveImage $file): bool => $file->width() > 0 && $file->width() <= self::MAX_WIDTH)
            ->sortBy(fn (ResponsiveImage $file): int => $file->width())
            ->values();

        $largest = $files->last();

        return [
            'src' => $conversion !== '' ? $media->getUrl($conversion) : ($largest?->url() ?? $media->getUrl()),
            'srcset' => $files->isEmpty()
                ? null
                : $files->map(fn (ResponsiveImage $file): string => $file->url().' '.$file->width().'w')->implode(', '),
            'width' => $largest?->width(),
            'height' => $largest?->height(),
        ];
    }
}
