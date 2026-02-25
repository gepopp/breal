<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Spatie\Sitemap\SitemapGenerator;

class SitemapController extends Controller
{
    public function generate()
    {
        File::delete(public_path('sitemap.xml'));
        SitemapGenerator::create(route('hausverwaltung.home'))->writeToFile(public_path('sitemap.xml'));
    }
}
