<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use UnitEnum;

class SearchSettings extends SettingsPage
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-magnifying-glass';

    protected static ?string $navigationLabel = 'Immobiliensuche';

    protected static UnitEnum|string|null $navigationGroup = 'Seiteneinstellungen';

    protected static ?string $title = 'Immobiliensuche Einstellungen';

    protected static string $settings = \App\Settings\SearchSettings::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Immobiliensuche')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('search_header_de')
                                    ->label('Header (Deutsch)')
                                    ->required(),
                                Forms\Components\TextInput::make('search_header_en')
                                    ->label('Header (English)')
                                    ->required(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('search_subheader_de')
                                    ->label('Unterzeile (Deutsch)')
                                    ->required(),
                                Forms\Components\TextInput::make('search_subheader_en')
                                    ->label('Subheader (English)')
                                    ->required(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Forms\Components\RichEditor::make('search_introtext_de')
                                    ->label('Introtext (Deutsch)')
                                    ->required(),
                                Forms\Components\RichEditor::make('search_introtext_en')
                                    ->label('Introtext (English)')
                                    ->required(),
                            ]),
                    ]),
            ])
            ->columns(1);
    }
}
