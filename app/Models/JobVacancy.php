<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class JobVacancy extends Model implements HasMedia, Sitemapable
{
    use HasSlug;
    use HasTranslations;
    use InteractsWithMedia;
    use SoftDeletes;

    public array $translatable = ['title', 'job_title', 'description'];

    protected $casts = [
        'from' => 'datetime',
        'to' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->usingLanguage('de')
            ->doNotGenerateSlugsOnUpdate()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function toSitemapTag(): Url|string|array
    {
        return Url::create(route('stellenanzeige', $this));
    }
}
