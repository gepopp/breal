<?php

use Illuminate\Support\Facades\Route;

Route::fallback(function () {
    return view('404');
});

Route::group(['as' => 'hausverwaltung.', 'middleware' => 'language'], function () {
    Route::get('/', \App\Livewire\Landing\FacilityManagment::class)->name('home');
    Route::get('/leistungen', \App\Livewire\Hausverwaltung\Leistungen::class)->name('leistungen');
    Route::get('/service', \App\Livewire\Hausverwaltung\Service::class)->name('service');
    Route::get('/karriere', \App\Livewire\Pages\Vacancies::class)->name('karriere');
    Route::get('/kontakt', \App\Livewire\Pages\Contact::class)->name('kontakt');
    Route::get('/team', \App\Livewire\Pages\Team::class)->name('team');
    Route::get('/faq', \App\Livewire\Pages\FAQ::class)->name('faq');
    Route::get('/leistung/{competence}', \App\Livewire\Pages\Compentence::class)->name('leistung');

});

Route::group(['prefix' => 'makler', 'as' => 'makler.', 'middleware' => 'language'], function () {
    Route::get('/start', \App\Livewire\Landing\RealEstate::class)->name('home');
    Route::get('/immobiliensuche', \App\Livewire\Makler\Immobiliensuche::class)->name('immobiliensuche');
    Route::get('/ueber-uns', \App\Livewire\Makler\Ueber::class)->name('ueber-uns');
    Route::get('/karriere', \App\Livewire\Pages\Vacancies::class)->name('karriere');
    Route::get('/kontakt', \App\Livewire\Pages\Contact::class)->name('kontakt');
    Route::get('/team', \App\Livewire\Pages\Team::class)->name('team');
    Route::get('/faq', \App\Livewire\Pages\FAQ::class)->name('faq');
    Route::get('/leistungen', \App\Livewire\Hausverwaltung\Leistungen::class)->name('leistungen');
    Route::get('/leistung/{competence}', \App\Livewire\Pages\Compentence::class)->name('leistung');
    Route::get('immobiliensuche', \App\Livewire\Pages\Immobiliensuche::class)->name('immobiliensuche');
    Route::get('immobilie/{realty}', \App\Livewire\Pages\Immobilie::class)->name('immobilie');
});

Route::group(['prefix' => 'technik', 'as' => 'technik.', 'middleware' => 'language'], function () {
    Route::get('/start', \App\Livewire\Landing\Technik::class)->name('home');
    Route::get('/karriere', \App\Livewire\Pages\Vacancies::class)->name('karriere');
    Route::get('/kontakt', \App\Livewire\Pages\Contact::class)->name('kontakt');
    Route::get('/team', \App\Livewire\Pages\Team::class)->name('team');
    Route::get('/faq', \App\Livewire\Pages\FAQ::class)->name('faq');
    Route::get('/leistungen', \App\Livewire\Hausverwaltung\Leistungen::class)->name('leistungen');
    Route::get('/leistung/{competence}', \App\Livewire\Pages\Compentence::class)->name('leistung');
});

Route::group(['middleware' => 'language'], function () {

    Route::get('/faq/{slug}', \App\Livewire\FAQSingle::class)->name('faq.single');
    Route::get('/stellenanzeige/{JobVacancy}', \App\Livewire\Pages\JobVacancy::class)->name('stellenanzeige');

    Route::get('impressum', \App\Livewire\Sites\Imprint::class)->name('impressum');
    Route::get('barrierefreiheit', \App\Livewire\Pages\AccessabilityDeclaration::class)->name('barrierefreiheit');
    Route::livewire('datenschutz', 'policy')->name('datenschutz');

    Route::view('/confirm-test', 'confirmed')->name('confirm-test');

    Route::get('confirmation/{id?}/{token?}', [\App\Http\Controllers\ContactRequestController::class, 'confirmation'])->name('confirm');
    Route::get('solve/{request}/{token}', [\App\Http\Controllers\ContactRequestController::class, 'solve'])->name('solve');

});

Route::livewire('verwalterservice', 'pages.verwalterservice')
    ->middleware('language')
    ->name('verwalterservice');

Route::get('import', function () {
    \App\Justimmo\Importer::import();
});

Route::get('sitemap', [\App\Http\Controllers\SitemapController::class, 'generate']);


Route::get('log-test', function(){
    \Illuminate\Support\Facades\Log::debug('Test');
});