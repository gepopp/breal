<?php

use App\Http\Controllers\ContactRequestController;
use App\Http\Controllers\SitemapController;
use App\Justimmo\Importer;
use App\Livewire\FAQSingle;
use App\Livewire\Hausverwaltung\Leistungen;
use App\Livewire\Hausverwaltung\Service;
use App\Livewire\Landing\FacilityManagment;
use App\Livewire\Landing\RealEstate;
use App\Livewire\Landing\Technik;
use App\Livewire\Makler\Immobiliensuche;
use App\Livewire\Makler\Ueber;
use App\Livewire\Pages\AccessabilityDeclaration;
use App\Livewire\Pages\Compentence;
use App\Livewire\Pages\Contact;
use App\Livewire\Pages\FAQ;
use App\Livewire\Pages\Immobilie;
use App\Livewire\Pages\JobVacancy;
use App\Livewire\Pages\Team;
use App\Livewire\Pages\Vacancies;
use App\Livewire\Sites\Imprint;
use Illuminate\Support\Facades\Route;

Route::fallback(function () {
    return view('404');
});

Route::group(['as' => 'hausverwaltung.', 'middleware' => 'language'], function () {
    Route::get('/', FacilityManagment::class)->name('home');
    Route::get('/leistungen', Leistungen::class)->name('leistungen');
    Route::get('/service', Service::class)->name('service');
    Route::get('/karriere', Vacancies::class)->name('karriere');
    Route::get('/kontakt', Contact::class)->name('kontakt');
    Route::get('/team', Team::class)->name('team');
    Route::get('/faq', FAQ::class)->name('faq');
    Route::get('/leistung/{competence}', Compentence::class)->name('leistung');

});

Route::group(['prefix' => 'makler', 'as' => 'makler.', 'middleware' => 'language'], function () {
    Route::get('/start', RealEstate::class)->name('home');
    Route::get('/immobiliensuche', Immobiliensuche::class)->name('immobiliensuche');
    Route::get('/ueber-uns', Ueber::class)->name('ueber-uns');
    Route::get('/karriere', Vacancies::class)->name('karriere');
    Route::get('/kontakt', Contact::class)->name('kontakt');
    Route::get('/team', Team::class)->name('team');
    Route::get('/faq', FAQ::class)->name('faq');
    //    Route::get('/leistungen', \App\Livewire\Hausverwaltung\Leistungen::class)->name('leistungen');
    Route::get('/leistung/{competence}', Compentence::class)->name('leistung');
    Route::get('immobiliensuche', App\Livewire\Pages\Immobiliensuche::class)->name('immobiliensuche');
    Route::get('immobilie/{realty}', Immobilie::class)->name('immobilie');
});

Route::group(['prefix' => 'technik', 'as' => 'technik.', 'middleware' => 'language'], function () {
    Route::get('/start', Technik::class)->name('home');
    Route::get('/karriere', Vacancies::class)->name('karriere');
    Route::get('/kontakt', Contact::class)->name('kontakt');
    Route::get('/team', Team::class)->name('team');
    Route::get('/faq', FAQ::class)->name('faq');
    Route::get('/leistungen', Leistungen::class)->name('leistungen');
    Route::get('/leistung/{competence}', Compentence::class)->name('leistung');
});

Route::group(['middleware' => 'language'], function () {

    Route::get('/faq/{slug}', FAQSingle::class)->name('faq.single');
    Route::get('/stellenanzeige/{JobVacancy}', JobVacancy::class)->name('stellenanzeige');

    Route::get('impressum', Imprint::class)->name('impressum');
    Route::get('barrierefreiheit', AccessabilityDeclaration::class)->name('barrierefreiheit');
    Route::livewire('datenschutz', 'policy')->name('datenschutz');

    Route::view('/confirm-test', 'confirmed')->name('confirm-test');

    Route::get('confirmation/{id?}/{token?}', [ContactRequestController::class, 'confirmation'])->name('confirm');
    Route::get('solve/{request}/{token}', [ContactRequestController::class, 'solve'])->name('solve');

});

Route::livewire('unternehmensservice', 'pages.verwalterservice')
    ->middleware(['language'])
    ->name('verwalterservice');

Route::get('import', function () {
    Importer::import();
});

Route::get('sitemap', [SitemapController::class, 'generate']);
