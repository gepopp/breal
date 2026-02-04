<?php

namespace App\Models;

use App\Enums\CompaniesEnum;
use App\Observers\DepartmentObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

#[ObservedBy(DepartmentObserver::class)]
class Department extends Model
{
    use HasFactory;
    use HasTranslations;

    public array $translatable = ['name'];

    protected $casts = ['company' => CompaniesEnum::class];

    protected static function booted(): void
    {
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('order');
        });
    }

    public function sub(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contactperson::class);

    }
}
