<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Number;
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

    /**
     * The salary as shown on the vacancy page, e.g. "63.000,00 € pro Jahr".
     */
    public function formattedSalary(): ?string
    {
        if (blank($this->salary)) {
            return null;
        }

        $amount = Number::currency((float) $this->salary, 'EUR', app()->getLocale());
        $unit = mb_strtolower((string) $this->unit_text);

        $interval = match (true) {
            str_starts_with($unit, 'stünd') => 'hour',
            str_starts_with($unit, 'wöch') => 'week',
            str_starts_with($unit, 'monat') => 'month',
            str_starts_with($unit, 'jähr') => 'year',
            default => null,
        };

        return $interval ? $amount.' '.__('vacancy.interval.'.$interval) : $amount;
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
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function toSitemapTag(): Url|string|array
    {
        return Url::create(route('stellenanzeige', $this));
    }
}
