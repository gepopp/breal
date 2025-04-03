<?php

namespace App\Filament\Pages;

use App\Forms\Components\SettingsUpload;
use App\Settings\LandingpageHausverwaltung as Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class LandingpageHausverwaltung extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = Setting::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Sektionen')
                    ->schema([
                        Forms\Components\Tabs\Tab::make('Intro')
                            ->schema([
                                SettingsUpload::make('hero_image')->required(),
                                Forms\Components\TextInput::make('hero_image_alt')->required(),
                                Forms\Components\TextInput::make('hero_header')->required(),
                                Forms\Components\TextInput::make('hero_subheader')->required(),
                                Forms\Components\RichEditor::make('hero_introtext')->required(),
                                Forms\Components\RichEditor::make('text')->required(),
                                SettingsUpload::make('text_image')->required(),
                                Forms\Components\TextInput::make('text_image_alt')->required(),
                            ])
                    ]),


            ])->columns(1);
    }
}
