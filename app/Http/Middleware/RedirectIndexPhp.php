<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The web server also answers "/index.php/service" with the page, which gave search engines every page twice (with
 * a self canonical). Sends such addresses permanently to the address without "/index.php", query string and
 * language prefix kept. Runs before ApplyLocalePrefix, which rewrites the request URI.
 */
class RedirectIndexPhp
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethodSafe() || ! preg_match('#^/index\.php(?=/|\?|$)(.*)$#', (string) $request->server->get('REQUEST_URI'), $matches)) {
            return $next($request);
        }

        $rest = $matches[1];

        if ($rest === '' || $rest[0] === '?') {
            $rest = '/'.$rest;
        }

        return redirect()->to($request->getSchemeAndHttpHost().$rest, 301);
    }
}
