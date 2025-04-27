<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class LandingpageMaklerSettings extends Settings
{
    public array|int|null $hero_images = null;


    public static function group(): string
    {
        return 'MaklerSettings';
    }
}