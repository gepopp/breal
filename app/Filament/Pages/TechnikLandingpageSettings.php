<?php

namespace App\Filament\Pages;

use App\Settings\TechnikLandingpageSettings as Settings;
use BackedEnum;
use Filament\Forms;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class TechnikLandingpageSettings extends SettingsPage
{
    protected static BackedEnum|string|null $navigationIcon = Heroicon::WrenchScrewdriver;

    protected static string $settings = Settings::class;

    protected static UnitEnum|string|null $navigationGroup = 'Landingpages';

    protected static ?string $navigationLabel = 'Technik';

    protected static ?string $title = 'Technik Landingpage Einstellungen';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Hero Bereich')
                ->schema([
                    Forms\Components\FileUpload::make('hero_image')
                        ->label('Hero Bild')
                        ->columnSpanFull(),
                    Grid::make(2)->schema([
                        Forms\Components\TextInput::make('hero_image_alt_de')
                            ->label('Bild Alt-Text (Deutsch)')
                            ->required(),
                        Forms\Components\TextInput::make('hero_image_alt_en')
                            ->label('Bild Alt-Text (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        Forms\Components\TextInput::make('hero_header_de')
                            ->label('Hero Header (Deutsch)')
                            ->required(),
                        Forms\Components\TextInput::make('hero_header_en')
                            ->label('Hero Header (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        Forms\Components\Textarea::make('hero_subheader_de')
                            ->label('Hero Subheader (Deutsch)')
                            ->required(),
                        Forms\Components\Textarea::make('hero_subheader_en')
                            ->label('Hero Subheader (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        Forms\Components\RichEditor::make('hero_introtext_de')
                            ->label('Hero Intro Text (Deutsch)')
                            ->required(),
                        Forms\Components\RichEditor::make('hero_introtext_en')
                            ->label('Hero Intro Text (English)')
                            ->required(),
                    ]),
                    Forms\Components\ToggleButtons::make('intro_layout')
                        ->label('Intro Layout')
                        ->options([
                            'two_columns' => 'Zwei Spalten',
                            'text_image' => 'Text mit Bild',
                        ])
                        ->default('two_columns')
                        ->grouped()
                        ->reactive()
                        ->columnSpanFull(),

                    Section::make('Zwei Spalten Layout')
                        ->schema([
                            Grid::make(2)->schema([
                                Forms\Components\RichEditor::make('hero_text_column_one_de')
                                    ->label('Text Spalte 1 (Deutsch)')
                                    ->required(),
                                Forms\Components\RichEditor::make('hero_text_column_one_en')
                                    ->label('Text Spalte 1 (English)')
                                    ->required(),
                            ]),
                            Grid::make(2)->schema([
                                Forms\Components\RichEditor::make('hero_text_column_two_de')
                                    ->label('Text Spalte 2 (Deutsch)')
                                    ->required(),
                                Forms\Components\RichEditor::make('hero_text_column_two_en')
                                    ->label('Text Spalte 2 (English)')
                                    ->required(),
                            ]),
                        ])
                        ->visible(fn ($get) => $get('intro_layout') === 'two_columns'),

                    Section::make('Text mit Bild Layout')
                        ->schema([
                            Grid::make(2)->schema([
                                Forms\Components\RichEditor::make('text_de')
                                    ->label('Text (Deutsch)')
                                    ->required(),
                                Forms\Components\RichEditor::make('text_en')
                                    ->label('Text (English)')
                                    ->required(),
                            ]),
                            Forms\Components\FileUpload::make('text_image')
                                ->label('Text Bild')
                                ->columnSpanFull(),
                            Grid::make(2)->schema([
                                Forms\Components\TextInput::make('text_image_alt_de')
                                    ->label('Text Bild Alt-Text (Deutsch)')
                                    ->required(),
                                Forms\Components\TextInput::make('text_image_alt_en')
                                    ->label('Text Bild Alt-Text (English)')
                                    ->required(),
                            ]),
                        ])
                        ->visible(fn ($get) => $get('intro_layout') === 'text_image'),
                ]),

            Section::make('About Bereich')
                ->schema([
                    Grid::make(2)->schema([
                        Forms\Components\TextInput::make('about_header_de')
                            ->label('About Header (Deutsch)')
                            ->required(),
                        Forms\Components\TextInput::make('about_header_en')
                            ->label('About Header (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        Forms\Components\TextInput::make('about_subheader_de')
                            ->label('About Subheader (Deutsch)')
                            ->required(),
                        Forms\Components\TextInput::make('about_subheader_en')
                            ->label('About Subheader (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        Forms\Components\RichEditor::make('about_text_de')
                            ->label('About Text (Deutsch)')
                            ->required(),
                        Forms\Components\RichEditor::make('about_text_en')
                            ->label('About Text (English)')
                            ->required(),
                    ]),
                    Forms\Components\FileUpload::make('about_image')
                        ->label('About Bild')
                        ->columnSpanFull(),
                    Grid::make(2)->schema([
                        Forms\Components\TextInput::make('about_image_alt_de')
                            ->label('About Bild Alt-Text (Deutsch)')
                            ->required(),
                        Forms\Components\TextInput::make('about_image_alt_en')
                            ->label('About Bild Alt-Text (English)')
                            ->required(),
                    ]),
                    Forms\Components\Textarea::make('about_video_embed_code')
                        ->label('About Video Embed Code')
                        ->columnSpanFull(),
                ]),
        ])->columns(1);
    }
}
