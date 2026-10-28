<?php

namespace App\Http\Middleware;

use App\Services\LocaleUrls;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Answers old addresses listed in config/redirects.php with a permanent
 * redirect. Runs after ApplyLocalePrefix, so the language prefix is kept.
 */
class RedirectLegacyUrls
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethodSafe()) {
            return $next($request);
        }

        $path = '/'.trim($request->getPathInfo(), '/');
        $query = (string) $request->server->get('QUERY_STRING');
        $redirects = config('redirects.permanent');

        $target = $redirects[$path.($query !== '' ? '?'.$query : '')] ?? $redirects[$path] ?? null;

        if ($target === null) {
            return $next($request);
        }

        [$targetPath, $targetQuery] = array_pad(explode('?', $target, 2), 2, null);

        $url = $request->getSchemeAndHttpHost()
            .LocaleUrls::prefixPath($targetPath, $request->attributes->get('locale', config('app.default_locale')))
            .($targetQuery !== null ? '?'.$targetQuery : '');

        return redirect()->to($url, 301);
    }
}
