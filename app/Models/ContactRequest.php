<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ContactRequest extends Model implements HasMedia
{
    use InteractsWithMedia, SoftDeletes;

    protected $casts = [
        'verified_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    /**
     * When the download links of the uploads in the mails stop working. S3 presigned URLs (signature version 4)
     * must expire in less than one week; exactly seven days is refused now and then, which made the mail fail.
     */
    public static function uploadLinkExpiresAt(): Carbon
    {
        return now()->addDays(6);
    }

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
