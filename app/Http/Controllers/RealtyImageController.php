<?php

namespace App\Http\Controllers;

use App\Services\RealtyImage;
use Illuminate\Support\Facades\Http;
use Spatie\Image\Enums\Constraint;
use Spatie\Image\Image;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

/**
 * Hands out a resized WebP copy of a property photo (see RealtyImage).
 *
 * The first request fetches the photo, scales it down and stores the result in public/realty-image; from then on the
 * web server serves it as a static file and this controller is no longer involved.
 */
class RealtyImageController extends Controller
{
    private const MAX_SOURCE_BYTES = 15 * 1024 * 1024;

    public function __invoke(int $width, string $key): BinaryFileResponse
    {
        $source = RealtyImage::sourceFromKey($key);

        abort_if($source === null || ! in_array($width, RealtyImage::WIDTHS, true), 404);

        $target = public_path('realty-image/'.$width.'/'.$key);

        if (! is_file($target)) {
            $this->createCopy($source, $width, $target);
        }

        return response()->file($target, [
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    private function createCopy(string $source, int $width, string $target): void
    {
        try {
            $response = Http::timeout(10)->get($source);
        } catch (Throwable) {
            abort(404);
        }

        abort_unless($response->successful() && strlen($response->body()) <= self::MAX_SOURCE_BYTES, 404);

        if (! is_dir(dirname($target))) {
            mkdir(dirname($target), 0755, true);
        }

        $suffix = bin2hex(random_bytes(4));
        $original = $target.'.'.$suffix.'.src';
        $temporary = $target.'.'.$suffix.'.webp';

        file_put_contents($original, $response->body());

        try {
            Image::load($original)
                ->width($width, [Constraint::PreserveAspectRatio, Constraint::DoNotUpsize])
                ->format('webp')
                ->quality(80)
                ->save($temporary);

            rename($temporary, $target);
        } catch (Throwable) {
            abort(404);
        } finally {
            @unlink($original);
            @unlink($temporary);
        }
    }
}
