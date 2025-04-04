<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelSettings\Models\SettingsProperty;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class Settings extends SettingsProperty implements HasMedia
{
    use InteractsWithMedia;

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('layout')
            ->width(1920);
    }
}
