<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * The site is German unless the URL carries the "/en" prefix, which
     * ApplyLocalePrefix records on the request.
     *
     * @param  Closure(Request):Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->attributes->get('locale', config('app.default_locale'));

        // Ensure locale is always a valid value
        if (! in_array($locale, ['de', 'en'])) {
            $locale = config('app.default_locale', 'de');
        }

        App::setLocale($locale);
        Carbon::setLocale($locale);

        return $next($request);
    }
}
