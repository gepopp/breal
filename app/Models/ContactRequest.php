<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactRequest extends Model
{
    use SoftDeletes;

    protected $casts = [
        'verified_at' => 'datetime',
        'sent_at'     => 'datetime',
    ];


    protected static function booted(): void
    {
        static::addGlobalScope('verified', function (Builder $builder) {
            $builder->whereNotNull('verified_at');
        });

        static::addGlobalScope('solved', function (Builder $builder) {
            $builder->whereNull('solved_at');
        });
    }
}
