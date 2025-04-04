<?php

namespace App\Filament\Pages;

use App\Forms\Components\SettingsUpload;
use App\Settings\LandingpageHausverwaltung as Setting;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
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
                                        SettingsUpload::make('text_image')->required(),
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
                        Forms\Components\Tabs\Tab::make('Timeline')
                            ->schema([
                                Forms\Components\TextInput::make('timeline_header')->required(),
                                Forms\Components\TextInput::make('timeline_subheader')->required(),
                                Forms\Components\RichEditor::make('timeline_intro')->required(),
                            ]),
                        Forms\Components\Tabs\Tab::make('Service')
                            ->schema([
                                Forms\Components\TextInput::make('service_heading')->required(),
                                Forms\Components\TextInput::make('service_subheading')->required(),
                                Forms\Components\RichEditor::make('service_introtext')->required(),
                            ]),
                        Forms\Components\Tabs\Tab::make('Referenzen')
                            ->schema([
                                Forms\Components\TextInput::make('reference_header')->required(),
                                Forms\Components\TextInput::make('reference_subheader')->required(),
                                Forms\Components\RichEditor::make('reference_introtext')->required(),
                            ]),
                        Forms\Components\Tabs\Tab::make('Kontakt')
                            ->schema([
                                Forms\Components\TextInput::make('contact_header')->required(),
                                Forms\Components\TextInput::make('contact_subheader')->required(),
                                Forms\Components\RichEditor::make('contact_introtext')->required(),
                            ]),
                    ])
                    ->persistTabInQueryString(true),


            ])->columns(1);
    }
}
