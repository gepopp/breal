<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request):Response $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('locale', config('app.locale'));

        // Ensure locale is always a valid value
        if (!in_array($locale, ['de', 'en'])) {
            $locale = config('app.fallback_locale', 'de');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
