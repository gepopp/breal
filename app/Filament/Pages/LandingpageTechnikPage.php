<?php

namespace App\Filament\Pages;

use App\Forms\Components\SettingsUpload;
use App\Settings\LandingpageTechnikSettings;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class LandingpageTechnikPage extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = LandingpageTechnikSettings::class;

    protected static ?string $navigationLabel = 'Landingpage Technik';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Sektionen')
                    ->schema([
                        Forms\Components\Tabs\Tab::make('Intro')
                            ->schema([
                                SettingsUpload::make('hero_image')->multiple()->reorderable()->panelLayout('grid')->required(),
                                Forms\Components\TextInput::make('hero_image_alt')->required(),
                                Forms\Components\TextInput::make('hero_header')->required(),
                                Forms\Components\TextInput::make('hero_subheader')->required(),
                                Forms\Components\RichEditor::make('hero_introtext')
                                    ->rules(['required', 'max:320', 'min:160'])
                                    ->required(),

                                Forms\Components\ToggleButtons::make('intro_layout')
                                    ->grouped()
                                    ->reactive()
                                    ->options(['two_columns' => 'two_columns', 'text_image' => 'text_image']),

                                Forms\Components\Section::make('two_columns')
                                    ->schema([
                                        Forms\Components\RichEditor::make('hero_text_column_one')->required(),
                                        Forms\Components\RichEditor::make('hero_text_column_two')->required(),
                                    ])
                                    ->visible(fn($get) => $get('intro_layout') === 'two_columns'),

                                Forms\Components\Section::make('text_image')
                                    ->schema([
                                        Forms\Components\RichEditor::make('text')->required(),
                                        SettingsUpload::make('text_image')->multiple()->reorderable()->panelLayout('grid')->required(),
                                        Forms\Components\TextInput::make('text_image_alt')->required(),
                                    ])
                                    ->visible(fn($get) => $get('intro_layout') === 'text_image'),


                            ]),
                        Forms\Components\Tabs\Tab::make('Über uns')
                            ->schema([
                                Forms\Components\TextInput::make('about_header')->required(),
                                Forms\Components\TextInput::make('about_subheader')->required(),
                                Forms\Components\RichEditor::make('about_text')->required(),
                                SettingsUpload::make('about_image')->required(),
                                Forms\Components\TextInput::make('about_image_alt')->required(),
                                Textarea::make('about_video_embed_code'),
                            ]),
                    ])
            ])->columns(1);
    }
}
