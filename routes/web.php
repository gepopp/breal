<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('impressum', \App\Livewire\Sites\Imprint::class)->name('impressum');
Volt::route('datenschutz', 'policy')->name('datenschutz');


Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});


Route::get('mail-test', function () {
   return new \App\Mail\VerificationEmail();
});

require __DIR__.'/auth.php';


Route::fallback(function () {
    return view('404');
});