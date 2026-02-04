<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use UnitEnum;

class VacanciesSettings extends SettingsPage
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'Karriereseite';

    protected static UnitEnum|string|null $navigationGroup = 'Seiteneinstellungen';

    protected static ?string $title = 'Karriereseite Einstellungen';

    protected static string $settings = \App\Settings\VacanciesSettings::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Karriereseite')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('vacancies_header_de')
                                    ->label('Header (Deutsch)')
                                    ->required(),
                                Forms\Components\TextInput::make('vacancies_header_en')
                                    ->label('Header (English)')
                                    ->required(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('vacancies_subheader_de')
                                    ->label('Unterzeile (Deutsch)')
                                    ->required(),
                                Forms\Components\TextInput::make('vacancies_subheader_en')
                                    ->label('Subheader (English)')
                                    ->required(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Forms\Components\RichEditor::make('vacancies_introtext_de')
                                    ->label('Introtext (Deutsch)')
                                    ->required(),
                                Forms\Components\RichEditor::make('vacancies_introtext_en')
                                    ->label('Introtext (English)')
                                    ->required(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Forms\Components\RichEditor::make('cold_application_cta_text_de')
                                    ->label('Initiativbewerbungs-CTA Text (Deutsch)'),
                                Forms\Components\RichEditor::make('cold_application_cta_text_en')
                                    ->label('Unsolicited Application CTA Text (English)'),
                            ]),
                    ]),
            ])
            ->columns(1);
    }
}
