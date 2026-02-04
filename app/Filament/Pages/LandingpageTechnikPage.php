<?php

namespace App\Filament\Pages;

use App\Forms\Components\SettingsUpload;
use App\Settings\LandingpageTechnikSettings;
use BackedEnum;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class LandingpageTechnikPage extends SettingsPage
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-document-text';

    protected static string $settings = LandingpageTechnikSettings::class;

    protected static ?string $navigationLabel = 'Landingpage Technik';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('Sektionen')
                    ->schema([
                        Tabs\Tab::make('Intro')
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

                                Section::make('two_columns')
                                    ->schema([
                                        Forms\Components\RichEditor::make('hero_text_column_one')->required(),
                                        Forms\Components\RichEditor::make('hero_text_column_two')->required(),
                                    ])
                                    ->visible(fn ($get) => $get('intro_layout') === 'two_columns'),

                                Section::make('text_image')
                                    ->schema([
                                        Forms\Components\RichEditor::make('text')->required(),
                                        SettingsUpload::make('text_image')->multiple()->reorderable()->panelLayout('grid')->required(),
                                        Forms\Components\TextInput::make('text_image_alt')->required(),
                                    ])
                                    ->visible(fn ($get) => $get('intro_layout') === 'text_image'),

                            ]),
                        Tabs\Tab::make('Über uns')
                            ->schema([
                                Forms\Components\TextInput::make('about_header')->required(),
                                Forms\Components\TextInput::make('about_subheader')->required(),
                                Forms\Components\RichEditor::make('about_text')->required(),
                                SettingsUpload::make('about_image')->required(),
                                Forms\Components\TextInput::make('about_image_alt')->required(),
                                Textarea::make('about_video_embed_code'),
                            ]),
                    ]),
            ])->columns(1);
    }
}
