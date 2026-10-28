<?php

namespace App\Models;

use App\Services\MediaImage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class Timeline extends Model implements HasMedia
{
    use HasTranslations;
    use InteractsWithMedia;

    public array $translatable = ['title', 'description'];

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion(MediaImage::CONVERSION)
            ->width(1000)
            ->format('webp')
            ->quality(80)
            ->withResponsiveImages();
    }

    protected static function booted(): void
    {
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('year');
        });
    }
}
