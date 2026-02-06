<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\ToggleButtons;
use App\Forms\Components\SettingsUpload;
use App\Settings\HausverwaltungLandingpageSettings as Settings;
use BackedEnum;
use Filament\Forms;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class HausverwaltungLandingpageSettings extends SettingsPage
{
    protected static string | BackedEnum | null $navigationIcon = Heroicon::Home;

    protected static string $settings = Settings::class;

    protected static string | UnitEnum | null $navigationGroup = 'Landingpages';

    protected static ?string $navigationLabel = 'Hausverwaltung';

    protected static ?string $title = 'Hausverwaltung Landingpage Einstellungen';

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
                    TextInput::make('hero_speed')
                        ->label('Hero Geschwindigkeit (ms)')
                        ->numeric()
                        ->default(4000)
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

            Section::make('Timeline Bereich')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('timeline_header_de')
                            ->label('Timeline Header (Deutsch)')
                            ->required(),
                        TextInput::make('timeline_header_en')
                            ->label('Timeline Header (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('timeline_subheader_de')
                            ->label('Timeline Subheader (Deutsch)')
                            ->required(),
                        TextInput::make('timeline_subheader_en')
                            ->label('Timeline Subheader (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        RichEditor::make('timeline_intro_de')
                            ->label('Timeline Intro (Deutsch)')
                            ->required(),
                        RichEditor::make('timeline_intro_en')
                            ->label('Timeline Intro (English)')
                            ->required(),
                    ]),
                    TextInput::make('timeline_speed')
                        ->label('Timeline Geschwindigkeit (ms)')
                        ->numeric()
                        ->default(4000)
                        ->columnSpanFull(),
                ]),

            Section::make('Service Bereich')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('service_heading_de')
                            ->label('Service Heading (Deutsch)')
                            ->required(),
                        TextInput::make('service_heading_en')
                            ->label('Service Heading (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('service_subheading_de')
                            ->label('Service Subheading (Deutsch)')
                            ->required(),
                        TextInput::make('service_subheading_en')
                            ->label('Service Subheading (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        RichEditor::make('service_introtext_de')
                            ->label('Service Intro Text (Deutsch)')
                            ->required(),
                        RichEditor::make('service_introtext_en')
                            ->label('Service Intro Text (English)')
                            ->required(),
                    ]),
                ]),

            Section::make('Kontakt Bereich')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('contact_header_de')
                            ->label('Kontakt Header (Deutsch)')
                            ->required(),
                        TextInput::make('contact_header_en')
                            ->label('Kontakt Header (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('contact_subheader_de')
                            ->label('Kontakt Subheader (Deutsch)')
                            ->required(),
                        TextInput::make('contact_subheader_en')
                            ->label('Kontakt Subheader (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        RichEditor::make('contact_introtext_de')
                            ->label('Kontakt Intro Text (Deutsch)')
                            ->required(),
                        RichEditor::make('contact_introtext_en')
                            ->label('Kontakt Intro Text (English)')
                            ->required(),
                    ]),
                ]),

            Section::make('Referenzen Bereich')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('reference_header_de')
                            ->label('Referenz Header (Deutsch)')
                            ->required(),
                        TextInput::make('reference_header_en')
                            ->label('Referenz Header (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('reference_subheader_de')
                            ->label('Referenz Subheader (Deutsch)')
                            ->required(),
                        TextInput::make('reference_subheader_en')
                            ->label('Referenz Subheader (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        RichEditor::make('reference_introtext_de')
                            ->label('Referenz Intro Text (Deutsch)')
                            ->required(),
                        RichEditor::make('reference_introtext_en')
                            ->label('Referenz Intro Text (English)')
                            ->required(),
                    ]),
                ]),

            Section::make('Kompetenzen Bereich')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('competence_header_de')
                            ->label('Kompetenz Header (Deutsch)')
                            ->required(),
                        TextInput::make('competence_header_en')
                            ->label('Kompetenz Header (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('competence_subheader_de')
                            ->label('Kompetenz Subheader (Deutsch)')
                            ->required(),
                        TextInput::make('competence_subheader_en')
                            ->label('Kompetenz Subheader (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        RichEditor::make('competence_introtext_de')
                            ->label('Kompetenz Intro Text (Deutsch)')
                            ->required(),
                        RichEditor::make('competence_introtext_en')
                            ->label('Kompetenz Intro Text (English)')
                            ->required(),
                    ]),
                ]),
        ])->columns(1);
    }
}
