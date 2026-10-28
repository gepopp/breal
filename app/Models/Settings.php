<?php

namespace App\Models;

use App\Services\MediaImage;
use Spatie\LaravelSettings\Models\SettingsProperty;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Settings extends SettingsProperty implements HasMedia
{
    use InteractsWithMedia;

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion(MediaImage::CONVERSION)
            ->width(MediaImage::MAX_WIDTH)
            ->format('webp')
            ->quality(80)
            ->withResponsiveImages();

        $this->addMediaConversion('layout')
            ->width(1920);

        $this->addMediaConversion('slider')
            ->width(1600)
            ->height(1200);
    }
}
