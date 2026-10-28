<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms\Components\RichEditor;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use UnitEnum;

class LegalSettings extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-scale';

    protected static ?string $navigationLabel = 'Rechtliches';

    protected static string|UnitEnum|null $navigationGroup = 'Seiteneinstellungen';

    protected static ?string $title = 'Rechtliche Seiten Einstellungen';

    protected static string $settings = \App\Settings\LegalSettings::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Impressum')
                    ->description('Das Impressum wird aus den Firmendaten in config/company.php erzeugt und ist hier nicht bearbeitbar.')
                    ->schema([]),

                Section::make('Datenschutz')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                RichEditor::make('dpgr_text_de')
                                    ->label('Datenschutz (Deutsch)')
                                    ->required(),
                                RichEditor::make('dpgr_text_en')
                                    ->label('Data Protection (English)')
                                    ->required(),
                            ]),
                    ]),

                Section::make('Barrierefreiheit')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                RichEditor::make('accessability_text_de')
                                    ->label('Barrierefreiheit (Deutsch)')
                                    ->required(),
                                RichEditor::make('accessability_text_en')
                                    ->label('Accessibility (English)')
                                    ->required(),
                            ]),
                    ]),
            ])
            ->columns(1);
    }
}
