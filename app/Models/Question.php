<?php

namespace App\Models;

use App\Services\HtmlTrailingSpace;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class Question extends Model implements HasMedia
{
    use HasSlug;
    use HasTranslations;
    use InteractsWithMedia;
    use SoftDeletes;

    public array $translatable = ['question', 'excerpt', 'answer'];

    protected $appends = ['link'];

    protected function casts(): array
    {
        return [
            'stand' => 'date',
        ];
    }

    protected function link(): Attribute
    {
        return Attribute::make(get: fn () => route('faq.single', $this->slug));
    }

    protected static function booted(): void
    {
        static::saving(function (Question $question): void {
            foreach ($question->getTranslations('answer') as $locale => $answer) {
                if (is_string($answer) && ($trimmed = HtmlTrailingSpace::trim($answer)) !== $answer) {
                    $question->setTranslation('answer', $locale, $trimmed);
                }
            }
        });

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('order');
        });
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->usingLanguage('de')
            ->doNotGenerateSlugsOnUpdate()
            ->generateSlugsFrom('question')
            ->saveSlugsTo('slug');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('for_og')
            ->width(1200)
            ->height(627);
    }
}
