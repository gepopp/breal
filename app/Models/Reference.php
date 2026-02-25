<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class Reference extends Model implements HasMedia
{
    use HasSlug;
    use HasTranslations;
    use InteractsWithMedia;

    public array $translatable = ['title', 'description', 'testimonial'];

    protected $casts = [
        'tags' => 'array',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->usingLanguage('de')
            ->doNotGenerateSlugsOnUpdate()
            ->generateSlugsFrom('title')
            ->doNotGenerateSlugsOnUpdate()
            ->saveSlugsTo('slug');
    }
}
