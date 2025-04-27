<?php

namespace App\Filament\Pages;

use App\Forms\Components\SettingsUpload;
use App\Settings\MaklerSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class LandingpageMaklerSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = \App\Settings\LandingpageMaklerSettings::class;

    protected static null|string $navigationLabel = 'Landingpage Makler';
    protected static null|string $title = 'Landingpage Makler';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                SettingsUpload::make('hero_images')
                    ->multiple()
                    ->reorderable()
                    ->panelLayout('grid')
                    ->required(),
            ])->columns(1);
    }
}
