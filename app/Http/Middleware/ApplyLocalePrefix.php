<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Facade;
use Symfony\Component\HttpFoundation\Response;

/**
 * Serves the English site under the "/en" URL prefix.
 *
 * The prefix is stripped before routing, so every page keeps a single route
 * and route name. Generated links get the prefix back via the path formatter
 * registered in the AppServiceProvider.
 */
class ApplyLocalePrefix
{
    public const PREFIXED_LOCALE = 'en';

    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! preg_match('#^/'.self::PREFIXED_LOCALE.'(?=/|$)(.*)$#', $request->getPathInfo(), $matches)) {
            return $next($request);
        }

        $path = $matches[1] === '' ? '/' : $matches[1];
        $queryString = (string) $request->server->get('QUERY_STRING');

        $server = $request->server->all();
        $server['REQUEST_URI'] = $path.($queryString !== '' ? '?'.$queryString : '');

        $rewritten = $request->duplicate(server: $server);
        $rewritten->attributes->set('locale', self::PREFIXED_LOCALE);

        app()->instance('request', $rewritten);
        Facade::clearResolvedInstance('request');
        App::setLocale(self::PREFIXED_LOCALE);

        return $next($rewritten);
    }
}
