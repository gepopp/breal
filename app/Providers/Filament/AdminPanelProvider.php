<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\EditProfile;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->passwordReset()
            ->multiFactorAuthentication([
                AppAuthentication::make()
                    ->recoverable(),
            ], isRequired: true)
            ->spa()
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->colors([
                'primary' => [
                    '50' => '#e1f2ff',
                    '100' => '#c2e3ff',
                    '200' => '#79cbff',
                    '300' => '#00a3ff',
                    '400' => '#004e79',
                    '500' => '#002641',
                    '600' => '#001e39',
                    '700' => '#00182e',
                    '800' => '#001426',
                    '900' => '#00101f',
                    '950' => '#000811',
                ],
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Default widgets removed - using custom export widgets
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->favicon(fn () => asset('favicon.png'))
            ->brandLogo(asset('logos/bereal_immobilien.svg'))
            ->brandLogoHeight('2rem')
            ->authMiddleware([
                Authenticate::class,
            ])
            ->databaseNotifications()
            ->profile(EditProfile::class);
    }
}
