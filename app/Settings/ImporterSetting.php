<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ImporterSetting extends Settings
{
    public string $last_import = '';

    public static function group(): string
    {
        return 'importer';
    }

    public function toArray(): array
    {
        // No translatable text properties in this settings class
        return [];
    }
}
