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

class VacanciesSettings extends SettingsPage
{
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'Karriereseite';

    protected static string | UnitEnum | null $navigationGroup = 'Seiteneinstellungen';

    protected static ?string $title = 'Karriereseite Einstellungen';

    protected static string $settings = \App\Settings\VacanciesSettings::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Karriereseite')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('vacancies_header_de')
                                    ->label('Header (Deutsch)')
                                    ->required(),
                                TextInput::make('vacancies_header_en')
                                    ->label('Header (English)')
                                    ->required(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('vacancies_subheader_de')
                                    ->label('Unterzeile (Deutsch)')
                                    ->required(),
                                TextInput::make('vacancies_subheader_en')
                                    ->label('Subheader (English)')
                                    ->required(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                RichEditor::make('vacancies_introtext_de')
                                    ->label('Introtext (Deutsch)')
                                    ->required(),
                                RichEditor::make('vacancies_introtext_en')
                                    ->label('Introtext (English)')
                                    ->required(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                RichEditor::make('cold_application_cta_text_de')
                                    ->label('Initiativbewerbungs-CTA Text (Deutsch)'),
                                RichEditor::make('cold_application_cta_text_en')
                                    ->label('Unsolicited Application CTA Text (English)'),
                            ]),
                    ]),
            ])
            ->columns(1);
    }
}
