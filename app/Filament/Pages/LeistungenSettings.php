<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use UnitEnum;

class LeistungenSettings extends SettingsPage
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Leistungsseiten';

    protected static UnitEnum|string|null $navigationGroup = 'Seiteneinstellungen';

    protected static ?string $title = 'Leistungsseiten Einstellungen';

    protected static string $settings = \App\Settings\LeistungenSettings::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Hausverwaltung Leistungen')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('hausverwaltung_leistungen_header_de')
                                    ->label('Header (Deutsch)')
                                    ->required(),
                                Forms\Components\TextInput::make('hausverwaltung_leistungen_header_en')
                                    ->label('Header (English)')
                                    ->required(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('hausverwaltung_leistungen_subheader_de')
                                    ->label('Unterzeile (Deutsch)')
                                    ->required(),
                                Forms\Components\TextInput::make('hausverwaltung_leistungen_subheader_en')
                                    ->label('Subheader (English)')
                                    ->required(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Forms\Components\RichEditor::make('hausverwaltung_leistungen_introtext_de')
                                    ->label('Introtext (Deutsch)')
                                    ->required(),
                                Forms\Components\RichEditor::make('hausverwaltung_leistungen_introtext_en')
                                    ->label('Introtext (English)')
                                    ->required(),
                            ]),
                    ]),

                Section::make('Immobilien/Makler Leistungen')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('immobilien_leistungen_header_de')
                                    ->label('Header (Deutsch)')
                                    ->required(),
                                Forms\Components\TextInput::make('immobilien_leistungen_header_en')
                                    ->label('Header (English)')
                                    ->required(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('immobilien_leistungen_subheader_de')
                                    ->label('Unterzeile (Deutsch)')
                                    ->required(),
                                Forms\Components\TextInput::make('immobilien_leistungen_subheader_en')
                                    ->label('Subheader (English)')
                                    ->required(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Forms\Components\RichEditor::make('immobilien_leistungen_introtext_de')
                                    ->label('Introtext (Deutsch)')
                                    ->required(),
                                Forms\Components\RichEditor::make('immobilien_leistungen_introtext_en')
                                    ->label('Introtext (English)')
                                    ->required(),
                            ]),
                    ]),

                Section::make('Technik Leistungen')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('technik_leistungen_header_de')
                                    ->label('Header (Deutsch)')
                                    ->required(),
                                Forms\Components\TextInput::make('technik_leistungen_header_en')
                                    ->label('Header (English)')
                                    ->required(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('technik_leistungen_subheader_de')
                                    ->label('Unterzeile (Deutsch)')
                                    ->required(),
                                Forms\Components\TextInput::make('technik_leistungen_subheader_en')
                                    ->label('Subheader (English)')
                                    ->required(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Forms\Components\RichEditor::make('technik_leistungen_introtext_de')
                                    ->label('Introtext (Deutsch)')
                                    ->required(),
                                Forms\Components\RichEditor::make('technik_leistungen_introtext_en')
                                    ->label('Introtext (English)')
                                    ->required(),
                            ]),
                    ]),
            ])
            ->columns(1);
    }
}
