<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Question extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasSlug;

    protected $appends = ['link'];

    protected function link(): Attribute
    {
        return Attribute::make( get: fn() => route('faq.single', $this->slug ));
    }


    protected static function booted(): void
    {
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('order');
        });
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('question')
            ->saveSlugsTo('slug');
    }
}
