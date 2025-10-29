<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

//
//Schedule::call(function () {
//    \App\Justimmo\Importer::import();
//})->hourly();

Schedule::call(function () {
    (new \App\Http\Controllers\SitemapController())->generate();
})->daily();
