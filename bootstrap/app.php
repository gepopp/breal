<?php

use App\Http\Middleware\ApplyLocalePrefix;
use App\Http\Middleware\RedirectLegacyUrls;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            if (app()->environment('local', 'testing')) {
                Route::middleware('web')
                    ->group(base_path('routes/justimmo-test.php'));
            }
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->prepend([
            ApplyLocalePrefix::class,
            RedirectLegacyUrls::class,
        ]);

        // Outermost, so redirects and error pages carry the headers as well.
        $middleware->prepend(SecurityHeaders::class);

        $middleware->web(append: [
            SetLocale::class,
        ]);

        // The cookie consent routes run without the web middleware and set the consent cookie unencrypted;
        // if the pages tried to decrypt it, it would be dropped and the banner would return on every page.
        $middleware->encryptCookies(except: [
            Str::slug(env('APP_NAME', 'laravel'), '_').'_cookie_consent',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->expectsJson() || $request->is('admin', 'admin/*', 'livewire*', 'log-viewer*', 'up')) {
                return null;
            }

            return response()->view('404', [], 404);
        });

        $exceptions->context(function () {
            $context = [];

            if (app()->runningInConsole()) {
                $context['running_in'] = 'console';
            } else {
                $request = request();
                $context += array_filter([
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'route' => $request->route()?->getName(),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'referer' => $request->headers->get('referer'),
                    'session_id' => $request->hasSession() ? $request->session()->getId() : null,
                    'livewire' => $request->header('X-Livewire') ? true : null,
                ], fn ($v) => $v !== null && $v !== '');
            }

            if ($user = auth()->user()) {
                $context['user_id'] = $user->getAuthIdentifier();
                $context['user_email'] = $user->email ?? null;
            }

            return $context;
        });
    })->create();
