<?php

namespace App\Models;

use App\Services\MediaImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
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

    /**
     * The tags are entered as free text, often as "#one #two" in a single entry.
     *
     * @return Collection<int, string>
     */
    public function hashtags(): Collection
    {
        return collect($this->tags)
            ->flatMap(fn (string $tag): array => preg_split('/[\s#,]+/', $tag, -1, PREG_SPLIT_NO_EMPTY))
            ->unique()
            ->values();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion(MediaImage::CONVERSION)
            ->width(1200)
            ->format('webp')
            ->quality(80)
            ->withResponsiveImages();
    }

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
