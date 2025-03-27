<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/welcome', function () { return view('welcome'); });


Route::group(['as' => 'hausverwaltung.'], function (){
    Route::get('/', \App\Livewire\Landing\FacilityManagment::class )->name('home');
    Route::get('/leistungen', \App\Livewire\Hausverwaltung\Leistungen::class)->name('leistungen');
    Route::get('/service', \App\Livewire\Hausverwaltung\Service::class)->name('service');
    Route::get('/karriere', \App\Livewire\Hausverwaltung\Karriere::class)->name('karriere');
    Route::get('/kontakt', \App\Livewire\Hausverwaltung\Kontakt::class)->name('kontakt');
});


Route::group(['prefix' => 'immobilien', 'as' => 'immobilien.'], function (){
    Route::get('/start', \App\Livewire\Landing\RealEstate::class )->name('home');
    Route::get('/immobiliensuche', \App\Livewire\Makler\Immobiliensuche::class )->name('immobiliensuche');
    Route::get('/ueber-uns', \App\Livewire\Makler\Ueber::class)->name('ueber-uns');
    Route::get('/karriere', \App\Livewire\Makler\Karriere::class)->name('karriere');
    Route::get('/kontakt', \App\Livewire\Makler\Kontakt::class)->name('kontakt');
});

Route::group(['prefix' => 'technik', 'as' => 'technik.'], function (){
    Route::get('/start', \App\Livewire\Landing\Technik::class )->name('home');
    Route::get('/karriere', \App\Livewire\Technik\Karriere::class)->name('karriere');
    Route::get('/kontakt', \App\Livewire\Technik\Kontakt::class)->name('kontakt');
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

Route::get('admins', function (){
    foreach (['gerhard@poppgerhard.at' => 'Gerhard', 'ronald@ivalu.eu' => 'Ronald', 'katharina@ivalu.eu' => 'Katharina'] as $email => $name) {
        \App\Models\User::updateOrCreate(['email' => $email], [
            'name' => $name,
            'password' => \Illuminate\Support\Facades\Hash::make($name),
            'admin' => true
        ]);
    }


});


require __DIR__.'/auth.php';


Route::fallback(function () {
    return view('404');
});


