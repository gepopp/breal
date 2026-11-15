<?php

namespace App\Models;

use App\Enums\CompaniesEnum;
use App\Services\PageMeta;
use Illuminate\Database\Eloquent\Builder;
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
    use HasSlug;
    use HasTranslations;
    use InteractsWithMedia;

    public array $translatable = ['name', 'description', 'body', 'meta_description'];

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

    /**
     * Text for the search result: the meta description entered for the current language, else the intro text.
     * An English page never shows the German meta description.
     */
    public function metaDescription(): string
    {
        $text = $this->getTranslation('meta_description', app()->getLocale(), false);

        // An entered text is shown as written (the form allows 200 characters); the intro text is cut like before.
        return filled($text) ? PageMeta::excerpt($text, 200) : PageMeta::excerpt($this->description);
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
