<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::group(['as' => 'hausverwaltung.'], function (){
    Route::get('/', \App\Livewire\Landing\FacilityManagment::class )->name('home');

});


Route::group(['prefix' => 'immobilien', 'as' => 'immobilien.'], function (){
    Route::get('/immobilien', \App\Livewire\Landing\RealEstate::class )->name('home');

});

Route::group(['prefix' => 'technik', 'as' => 'technik.'], function (){
    Route::get('/technik', \App\Livewire\Landing\Technik::class )->name('home');

});


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