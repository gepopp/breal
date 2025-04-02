<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelSettings\Models\SettingsProperty;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Settings extends SettingsProperty implements HasMedia
{
    use InteractsWithMedia;
}
