<?php

namespace App\Models;

use App\Enums\CompaniesEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class Competence extends Model implements HasMedia, Sitemapable
{
    use HasFactory;
    use HasSlug;
    use HasTranslations;
    use InteractsWithMedia;

    public array $translatable = ['name', 'description', 'body'];

    protected $casts = [
        'company' => CompaniesEnum::class,
        'on_landing' => 'boolean',
        'on_dropdown' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('order');
        });
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(250)
            ->height(250)
            ->sharpen(10);

        $this->addMediaConversion('article_header')
            ->width(1152)
            ->height((1152 / 16) * 9)
            ->sharpen(10);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->usingLanguage('de')
            ->doNotGenerateSlugsOnUpdate()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function toSitemapTag(): Url|string|array
    {
        return [
            Url::create(route('hausverwaltung.leistung', $this)),
            Url::create(route('makler.leistung', $this)),
            Url::create(route('technik.leistung', $this)),
        ];
    }
}
