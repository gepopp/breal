<?php

namespace App\Filament\Pages;

use App\Settings\LandingpageTechnik;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class LandingpageTechnik extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = LandingpageTechnik::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // ...
            ]);
    }
}
