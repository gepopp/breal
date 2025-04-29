<?php

namespace App\Filament\Pages;

use App\Forms\Components\SettingsUpload;
use App\Settings\MaklerSettings;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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

                Forms\Components\Tabs::make('Sektionen')
                    ->schema([
                        Forms\Components\Tabs\Tab::make('Intro')
                            ->schema([
                                SettingsUpload::make('hero_images')
                                    ->multiple()
                                    ->reorderable()
                                    ->panelLayout('grid')
                                    ->required(),

                                TextInput::make('intro_title'),
                                TextInput::make('intro_subtitle'),
                                Forms\Components\RichEditor::make('intro_description'),
                            ]),

                        Forms\Components\Tabs\Tab::make('Über uns')
                            ->schema([
                                SettingsUpload::make('about_image')->required(),
                                Forms\Components\TextInput::make('about_image_alt')->required(),
                                Forms\Components\TextInput::make('about_heading')->required(),
                                Forms\Components\RichEditor::make('about_text')->required(),
                            ]),

                        Forms\Components\Tabs\Tab::make('CTA')
                            ->schema([
                                Forms\Components\TextInput::make('cta_header')->required(),
                                Forms\Components\TextInput::make('cta_subheader')->required(),
                                Forms\Components\RichEditor::make('cta_text')->required(),
                                SettingsUpload::make('cta_image')->required(),
                                Forms\Components\TextInput::make('cta_image_alt')->required(),
                                Textarea::make('cta_video_embed_code'),
                            ]),


                    ])
            ])->columns(1);
    }
}
