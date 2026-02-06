<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use BackedEnum;
use Filament\Forms;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use UnitEnum;

class SearchSettings extends SettingsPage
{
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-magnifying-glass';

    protected static ?string $navigationLabel = 'Immobiliensuche';

    protected static string | UnitEnum | null $navigationGroup = 'Seiteneinstellungen';

    protected static ?string $title = 'Immobiliensuche Einstellungen';

    protected static string $settings = \App\Settings\SearchSettings::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Immobiliensuche')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('search_header_de')
                                    ->label('Header (Deutsch)')
                                    ->required(),
                                TextInput::make('search_header_en')
                                    ->label('Header (English)')
                                    ->required(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('search_subheader_de')
                                    ->label('Unterzeile (Deutsch)')
                                    ->required(),
                                TextInput::make('search_subheader_en')
                                    ->label('Subheader (English)')
                                    ->required(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                RichEditor::make('search_introtext_de')
                                    ->label('Introtext (Deutsch)')
                                    ->required(),
                                RichEditor::make('search_introtext_en')
                                    ->label('Introtext (English)')
                                    ->required(),
                            ]),
                    ]),
            ])
            ->columns(1);
    }
}
