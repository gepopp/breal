<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


//Schedule::call(function () {
//    \App\Justimmo\Importer::import();
//})->twiceDaily(['07:00', '19:00']);

Schedule::call(function () {
    (new \App\Http\Controllers\SitemapController())->generate();
})->daily();
