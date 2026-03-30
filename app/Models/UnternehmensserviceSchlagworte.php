<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnternehmensserviceSchlagworte extends Model
{
    use \Spatie\Translatable\HasTranslations;

    public array $translatable = ['name'];
}
