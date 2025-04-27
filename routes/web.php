<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Mail;

Route::get('/welcome', function () {
    return view('welcome');
});


Route::group(['as' => 'hausverwaltung.'], function () {
    Route::get('/', \App\Livewire\Landing\FacilityManagment::class)->name('home');
    Route::get('/leistungen', \App\Livewire\Hausverwaltung\Leistungen::class)->name('leistungen');
    Route::get('/service', \App\Livewire\Hausverwaltung\Service::class)->name('service');
    Route::get('/karriere', \App\Livewire\Pages\Vacancies::class)->name('karriere');
    Route::get('/kontakt', \App\Livewire\Pages\Contact::class)->name('kontakt');
    Route::get('/team', \App\Livewire\Pages\Team::class)->name('team');
});


Route::group(['prefix' => 'immobilien', 'as' => 'immobilien.'], function () {
    Route::get('/start', \App\Livewire\Landing\RealEstate::class)->name('home');
    Route::get('/immobiliensuche', \App\Livewire\Makler\Immobiliensuche::class)->name('immobiliensuche');
    Route::get('/ueber-uns', \App\Livewire\Makler\Ueber::class)->name('ueber-uns');
    Route::get('/karriere', \App\Livewire\Pages\Vacancies::class)->name('karriere');
    Route::get('/kontakt', \App\Livewire\Pages\Contact::class)->name('kontakt');
    Route::get('/team', \App\Livewire\Pages\Team::class)->name('team');
});

Route::group(['prefix' => 'technik', 'as' => 'technik.'], function () {
    Route::get('/start', \App\Livewire\Landing\Technik::class)->name('home');
    Route::get('/karriere', \App\Livewire\Pages\Vacancies::class)->name('karriere');
    Route::get('/kontakt', \App\Livewire\Pages\Contact::class)->name('kontakt');
    Route::get('/team', \App\Livewire\Pages\Team::class)->name('team');
});


Route::get('/stellenanzeige/{JobVacancy}', \App\Livewire\Pages\JobVacancy::class)->name('stellenanzeige');


Route::get('impressum', \App\Livewire\Sites\Imprint::class)->name('impressum');
Volt::route('datenschutz', 'policy')->name('datenschutz');

Route::view('/confirm-test', 'confirmed')->name('confirm-test');

Route::get('confirm/{formRequest}/{token}', function (\App\Models\ContactRequest $formRequest, $token) {
    if (!request()->hasValidSignature() || $formRequest->token !== $token) {
        abort(401);
    }

    if (is_null($formRequest->verified_at)) {
        $formRequest->update(['verified_at' => now()]);

        Mail::to($formRequest->email)->send(new \App\Mail\ContactRequestConfirmedMail());
        Mail::to(\App\Livewire\ContactForm::RECIPIENT)->send(new \App\Mail\ContactRequestSolvedMail($formRequest));
    }

    return view('confirmed');
})->name('confirm');


Route::get('solve-test', function () {

    $request = \App\Models\ContactRequest::inRandomOrder()->first();
    return view('solved', compact('request'));

})->name('solve-test');

Route::get('solve/{request}/{token}', function (\App\Models\ContactRequest $request, $token) {
    if (!$request->hasValidSignature() || $request->token !== $token) {
        abort(401);
    }

    if (is_null($request->solved_at)) {
        $request->update(['solved' => now()]);
    }

    return view('solved', compact('request'));

})->name('solve');


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

Route::get('mail-test-confirmed', function () {
    return new \App\Mail\ContactRequestConfirmedMail();
});

Route::get('admins', function () {
    foreach (['gerhard@poppgerhard.at' => 'Gerhard', 'ronald@ivalu.eu' => 'Ronald', 'katharina@ivalu.eu' => 'Katharina'] as $email => $name) {
        \App\Models\User::updateOrCreate(['email' => $email], [
            'name'     => $name,
            'password' => \Illuminate\Support\Facades\Hash::make($name),
            'admin'    => true
        ]);
    }


});

Route::get('/auth/redirect', function () {
    return Socialite::driver('linkedin')
        ->scopes([
            'w_member_social',
            'r_basicprofile',
            'r_emailaddress',
            'rw_organization_admin',
            'r_organization_social',
            'w_organization_social',
            'w_organization_social'
        ])
        ->redirect();
});

Route::get('/admin/oauth/callback/linkedin', function () {
    $user = Socialite::driver('linkedin')->user();

    $data = [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'token' => $user->token,
            'refreshToken' => $user->refreshToken,
            'expiresIn' => now()->addSeconds($user->expiresIn)->format('Y-m-d H:i:s'),
        ];

    $user = \App\Models\User::where('email', $user->getEmail())->first();

    $user->update(['linkedin' => $data]);

    return redirect('/admin/users/' . $user->id . '/edit');
});


require __DIR__ . '/auth.php';


Route::fallback(function () {
    return view('404');
});


