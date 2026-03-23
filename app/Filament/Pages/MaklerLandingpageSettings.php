<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use App\Forms\Components\SettingsUpload;
use App\Settings\MaklerLandingpageSettings as Settings;
use BackedEnum;
use Filament\Forms;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class MaklerLandingpageSettings extends SettingsPage
{
    protected static string | BackedEnum | null $navigationIcon = Heroicon::BuildingOffice;

    protected static string $settings = Settings::class;

    protected static string | UnitEnum | null $navigationGroup = 'Landingpages';

    protected static ?string $navigationLabel = 'Makler';

    protected static ?string $title = 'Makler Landingpage Einstellungen';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Hero Bereich')
                ->schema([
                    SettingsUpload::make('hero_images')
                        ->label('Hero Bilder')
                        ->disk('s3')
                        ->multiple()
                        ->reorderable()
                        ->panelLayout('grid')
                        ->columnSpanFull(),
                ]),

            Section::make('Intro Bereich')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('intro_title_de')
                            ->label('Intro Titel (Deutsch)')
                            ->required(),
                        TextInput::make('intro_title_en')
                            ->label('Intro Titel (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        Textarea::make('intro_subtitle_de')
                            ->label('Intro Untertitel (Deutsch)')
                            ->required(),
                        Textarea::make('intro_subtitle_en')
                            ->label('Intro Untertitel (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        RichEditor::make('intro_description_de')
                            ->label('Intro Beschreibung (Deutsch)')
                            ->required(),
                        RichEditor::make('intro_description_en')
                            ->label('Intro Beschreibung (English)')
                            ->required(),
                    ]),
                ]),

            Section::make('CTA Bereich')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('cta_header_de')
                            ->label('CTA Header (Deutsch)')
                            ->required(),
                        TextInput::make('cta_header_en')
                            ->label('CTA Header (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('cta_subheader_de')
                            ->label('CTA Subheader (Deutsch)')
                            ->required(),
                        TextInput::make('cta_subheader_en')
                            ->label('CTA Subheader (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        RichEditor::make('cta_text_de')
                            ->label('CTA Text (Deutsch)')
                            ->required(),
                        RichEditor::make('cta_text_en')
                            ->label('CTA Text (English)')
                            ->required(),
                    ]),
                    SettingsUpload::make('cta_image')
                        ->disk('s3')
                        ->label('CTA Bild')
                        ->columnSpanFull(),
                    Grid::make(2)->schema([
                        TextInput::make('cta_image_alt_de')
                            ->label('CTA Bild Alt-Text (Deutsch)')
                            ->required(),
                        TextInput::make('cta_image_alt_en')
                            ->label('CTA Bild Alt-Text (English)')
                            ->required(),
                    ]),
                    Textarea::make('cta_video_embed_code')
                        ->label('CTA Video Embed Code')
                        ->columnSpanFull(),
                ]),

            Section::make('About Bereich')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('about_heading_de')
                            ->label('About Überschrift (Deutsch)')
                            ->required(),
                        TextInput::make('about_heading_en')
                            ->label('About Überschrift (English)')
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
                        ->disk('s3')
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
                ]),
        ])->columns(1);
    }
}
