<?php

use App\Providers\AppServiceProvider;
use App\Providers\CookiesServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\IndexNowServiceProvider;
use App\Providers\VoltServiceProvider;

return [
    AppServiceProvider::class,
    CookiesServiceProvider::class,
    AdminPanelProvider::class,
    IndexNowServiceProvider::class,
    VoltServiceProvider::class,
];
