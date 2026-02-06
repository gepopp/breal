<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\ToggleButtons;
use App\Forms\Components\SettingsUpload;
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
    protected static string | BackedEnum | null $navigationIcon = Heroicon::WrenchScrewdriver;

    protected static string $settings = Settings::class;

    protected static string | UnitEnum | null $navigationGroup = 'Landingpages';

    protected static ?string $navigationLabel = 'Technik';

    protected static ?string $title = 'Technik Landingpage Einstellungen';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Hero Bereich')
                ->schema([
                    SettingsUpload::make('hero_image')
                        ->label('Hero Bild')
                        ->multiple()
                        ->reorderable()
                        ->panelLayout('grid')
                        ->columnSpanFull(),
                    Grid::make(2)->schema([
                        TextInput::make('hero_image_alt_de')
                            ->label('Bild Alt-Text (Deutsch)')
                            ->required(),
                        TextInput::make('hero_image_alt_en')
                            ->label('Bild Alt-Text (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('hero_header_de')
                            ->label('Hero Header (Deutsch)')
                            ->required(),
                        TextInput::make('hero_header_en')
                            ->label('Hero Header (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        Textarea::make('hero_subheader_de')
                            ->label('Hero Subheader (Deutsch)')
                            ->required(),
                        Textarea::make('hero_subheader_en')
                            ->label('Hero Subheader (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        RichEditor::make('hero_introtext_de')
                            ->label('Hero Intro Text (Deutsch)')
                            ->required(),
                        RichEditor::make('hero_introtext_en')
                            ->label('Hero Intro Text (English)')
                            ->required(),
                    ]),
                    ToggleButtons::make('intro_layout')
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
                                RichEditor::make('hero_text_column_one_de')
                                    ->label('Text Spalte 1 (Deutsch)')
                                    ->required(),
                                RichEditor::make('hero_text_column_one_en')
                                    ->label('Text Spalte 1 (English)')
                                    ->required(),
                            ]),
                            Grid::make(2)->schema([
                                RichEditor::make('hero_text_column_two_de')
                                    ->label('Text Spalte 2 (Deutsch)')
                                    ->required(),
                                RichEditor::make('hero_text_column_two_en')
                                    ->label('Text Spalte 2 (English)')
                                    ->required(),
                            ]),
                        ])
                        ->visible(fn ($get) => $get('intro_layout') === 'two_columns'),

                    Section::make('Text mit Bild Layout')
                        ->schema([
                            Grid::make(2)->schema([
                                RichEditor::make('text_de')
                                    ->label('Text (Deutsch)')
                                    ->required(),
                                RichEditor::make('text_en')
                                    ->label('Text (English)')
                                    ->required(),
                            ]),
                            SettingsUpload::make('text_image')
                                ->label('Text Bild')
                                ->multiple()
                                ->reorderable()
                                ->panelLayout('grid')
                                ->columnSpanFull(),
                            Grid::make(2)->schema([
                                TextInput::make('text_image_alt_de')
                                    ->label('Text Bild Alt-Text (Deutsch)')
                                    ->required(),
                                TextInput::make('text_image_alt_en')
                                    ->label('Text Bild Alt-Text (English)')
                                    ->required(),
                            ]),
                        ])
                        ->visible(fn ($get) => $get('intro_layout') === 'text_image'),
                ]),

            Section::make('About Bereich')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('about_header_de')
                            ->label('About Header (Deutsch)')
                            ->required(),
                        TextInput::make('about_header_en')
                            ->label('About Header (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('about_subheader_de')
                            ->label('About Subheader (Deutsch)')
                            ->required(),
                        TextInput::make('about_subheader_en')
                            ->label('About Subheader (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        RichEditor::make('about_text_de')
                            ->label('About Text (Deutsch)')
                            ->required(),
                        RichEditor::make('about_text_en')
                            ->label('About Text (English)')
                            ->required(),
                    ]),
                    SettingsUpload::make('about_image')
                        ->label('About Bild')
                        ->columnSpanFull(),
                    Grid::make(2)->schema([
                        TextInput::make('about_image_alt_de')
                            ->label('About Bild Alt-Text (Deutsch)')
                            ->required(),
                        TextInput::make('about_image_alt_en')
                            ->label('About Bild Alt-Text (English)')
                            ->required(),
                    ]),
                    Textarea::make('about_video_embed_code')
                        ->label('About Video Embed Code')
                        ->columnSpanFull(),
                ]),
        ])->columns(1);
    }
}
