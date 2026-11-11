<?php

namespace App\Providers;

use App\Models\User;
use App\Services\EditorStyles;
use App\Services\LocaleUrls;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Http\Request;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Opcodes\LogViewer\Facades\LogViewer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();

        if ($this->app->runningInConsole()) {
            self::raiseMemoryLimit((string) config('queue.worker_memory_limit'));
        }

        // Registered after Livewire's own listener, so the editor stylesheet it inserts is already in the page.
        Event::listen(RequestHandled::class, [EditorStyles::class, 'defer']);

        // The cookie consent routes run without the web middleware and set the consent cookie unencrypted;
        // if the pages tried to decrypt it, it would be dropped and the banner would return on every page.
        // Read from the config (not env()), so it also works with a cached configuration.
        EncryptCookies::except(config('cookieconsent.cookie.name'));

        URL::formatPathUsing(function (string $path, ?Route $route): string {
            return LocaleUrls::isLocalizedRoute($route)
                ? LocaleUrls::prefixPath($path, app()->getLocale())
                : $path;
        });

        Gate::define('viewLogViewer', fn (?User $user): bool => (bool) $user?->admin);

        /*
                * Remote-Host-Auth für den Log-Viewer: Eine zentrale Log-Viewer-Instanz
                * ruft unsere /log-viewer/api-Routen ohne Session auf und schickt nur
                * einen Bearer-Token. Der viewLogViewer-Gate greift dann nicht (kein
                * eingeloggter User → 403). Diese Auth-Callback hat Vorrang vor dem Gate
                * und akzeptiert zuerst den geteilten Token, sonst den Browser-Admin.
                */
        LogViewer::auth(function (Request $request): bool {
            $expectedToken = config('log-viewer.remote_access_token');
            $providedToken = $request->bearerToken();

            if (filled($expectedToken) && filled($providedToken) && hash_equals($expectedToken, $providedToken)) {
                return true;
            }

            return (bool) $request->user()?->admin;
        });

        Queue::failing(function (JobFailed $event) {
            Log::error('Queue job failed', [
                'connection' => $event->connectionName,
                'queue' => $event->job->getQueue(),
                'job' => $event->job->resolveName(),
                'attempts' => $event->job->attempts(),
                'exception' => $event->exception->getMessage(),
                'exception_class' => $event->exception::class,
            ]);
        });
    }

    /**
     * Raises the PHP memory limit for console processes (queue worker, scheduler, artisan) to at least $wanted.
     * The CLI uses the global php.ini (128M on the server), which is too small for converting the 6000 px originals;
     * a higher limit is never lowered.
     */
    public static function raiseMemoryLimit(string $wanted): void
    {
        $wantedBytes = self::bytesFromIni($wanted);
        $currentBytes = self::bytesFromIni((string) ini_get('memory_limit'));

        if ($wantedBytes > 0 && $currentBytes >= 0 && $currentBytes < $wantedBytes) {
            ini_set('memory_limit', $wanted);
        }
    }

    /**
     * Converts a php.ini size such as "128M" or "1G" to bytes; "-1" (unlimited) stays -1, empty or invalid gives 0.
     */
    private static function bytesFromIni(string $value): int
    {
        $value = trim($value);

        if ($value === '-1') {
            return -1;
        }

        if (! preg_match('/^(\d+)\s*([KMG]?)$/i', $value, $matches)) {
            return 0;
        }

        return (int) $matches[1] * match (strtoupper($matches[2])) {
            'K' => 1024,
            'M' => 1024 ** 2,
            'G' => 1024 ** 3,
            default => 1,
        };
    }
}
