<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class FAQ extends Model implements Sitemapable
{
    use HasSlug;

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->usingLanguage('de')
            ->doNotGenerateSlugsOnUpdate()
            ->generateSlugsFrom('question')
            ->saveSlugsTo('slug');
    }

    public function toSitemapTag(): Url|string|array
    {
        return Url::create(route('faq.single', $this->slug));
    }
}
