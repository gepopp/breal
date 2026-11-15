<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Hands out media files from the own domain (MEDIA_URL=https://<host>/media).
 *
 * The object storage needs more than a second to the first byte, which made the hero image the slow LCP element.
 * The first request copies the file from the bucket into public/media; from then on the web server serves it
 * as a static file and this controller is no longer involved.
 */
class MediaProxyController extends Controller
{
    private const EXTENSIONS = ['webp', 'jpg', 'jpeg', 'png', 'gif', 'avif', 'svg'];

    public function __invoke(string $path): BinaryFileResponse
    {
        $path = ltrim($path, '/');
        $segments = explode('/', $path);

        abort_if(
            $path === ''
                || str_contains($path, '\\')
                || str_contains($path, "\0")
                || in_array('..', $segments, true)
                || in_array('', $segments, true)
                || ! in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), self::EXTENSIONS, true),
            404,
        );

        $target = public_path('media/'.$path);

        if (! is_file($target)) {
            $disk = Storage::disk(config('media-library.disk_name'));

            abort_unless($disk->exists($path), 404);

            if (! is_dir(dirname($target))) {
                mkdir(dirname($target), 0755, true);
            }

            $temporary = $target.'.'.bin2hex(random_bytes(4)).'.part';
            file_put_contents($temporary, $disk->get($path));
            rename($temporary, $target);
        }

        return response()->file($target, [
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
}
