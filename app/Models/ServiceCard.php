<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ServiceCard extends Model
{
    use HasFactory;
    use HasTranslations;

    public array $translatable = ['name', 'text'];

    protected $fillable = [
        'order',
        'name',
        'text',
        'icon',
        'type',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('order');
        });
    }
}
