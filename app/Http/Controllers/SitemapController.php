<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Sitemap\SitemapGenerator;

class SitemapController extends Controller
{
    public function generate()
    {
        SitemapGenerator::create(route('hausverwaltung.home'))->writeToFile(public_path('sitemap.xml'));
    }
}
