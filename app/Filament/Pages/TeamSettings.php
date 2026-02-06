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

class TeamSettings extends SettingsPage
{
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Teamseite';

    protected static string | UnitEnum | null $navigationGroup = 'Seiteneinstellungen';

    protected static ?string $title = 'Teamseite Einstellungen';

    protected static string $settings = \App\Settings\TeamSettings::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Teamseite')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('team_header_de')
                                    ->label('Header (Deutsch)')
                                    ->required(),
                                TextInput::make('team_header_en')
                                    ->label('Header (English)')
                                    ->required(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('team_subheader_de')
                                    ->label('Unterzeile (Deutsch)')
                                    ->required(),
                                TextInput::make('team_subheader_en')
                                    ->label('Subheader (English)')
                                    ->required(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                RichEditor::make('team_introtext_de')
                                    ->label('Introtext (Deutsch)')
                                    ->required(),
                                RichEditor::make('team_introtext_en')
                                    ->label('Introtext (English)')
                                    ->required(),
                            ]),
                    ]),
            ])
            ->columns(1);
    }
}
