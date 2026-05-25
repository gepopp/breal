<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
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
}
