<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ContactRequest extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    protected $casts = [
        'verified_at' => 'datetime',
        'sent_at'     => 'datetime',
    ];


//    public function registerMediaCollections(): void
//    {
//        $this->addMediaCollection('uploads')->use;
//
//    }


    protected static function booted(): void
    {
        static::addGlobalScope('latest', function (Builder $builder) {
            $builder->orderBy('verified_at', 'DESC');
        });

        static::addGlobalScope('verified', function (Builder $builder) {
            $builder->whereNotNull('verified_at');
        });

        static::addGlobalScope('solved', function (Builder $builder) {
            $builder->whereNull('solved_at');
        });
    }
}
