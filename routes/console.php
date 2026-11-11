<?php

use App\Justimmo\Importer;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Import Justimmo real estate data from uploaded zip files
Schedule::call(function () {
    Importer::import();
})->hourly();
