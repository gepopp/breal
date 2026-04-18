<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

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
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
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
