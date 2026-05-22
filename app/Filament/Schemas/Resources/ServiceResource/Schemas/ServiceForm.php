<?php

namespace App\Filament\Schemas\Resources\ServiceResource\Schemas;

use App\Enums\CompaniesEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Inhalte')
                    ->schema([
                        TextInput::make('name.de')
                            ->label('Bezeichnung auf der Startseite (Deutsch)')
                            ->required(),
                        TextInput::make('name.en')
                            ->label('Bezeichnung auf der Startseite (Englisch)'),
                        RichEditor::make('description.de')
                            ->label('Beschreibung (Deutsch)'),
                        RichEditor::make('description.en')
                            ->label('Beschreibung (Englisch)'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Select::make('company')
                    ->label('Unternehmen')
                    ->options(CompaniesEnum::class),

                Select::make('form')
                    ->label('Formular')
                    ->options([
                        'form' => 'Kontaktformular',
                        'address' => 'mit Adressfeld',
                    ])
                    ->nullable(),

                Repeater::make('links')
                    ->label('Dokumente')
                    ->schema([

                        FileUpload::make('path')
                            ->disk('hetzner')
                            ->visibility('public')
                            ->required()
                            ->downloadable()
                            ->openable()
                            ->previewable()
                            ->preserveFilenames()
                            ->acceptedFileTypes(['application/pdf'])
                            ->label('Dokument'),

                        TextInput::make('name')
                            ->label('Name')
                            ->required(),

                        Select::make('lang')
                            ->label('Sprache')
                            ->options([
                                'de' => 'Deutsch',
                                'en' => 'Englisch',
                            ])
                            ->default('de')
                            ->required(),

                    ])
                    ->columns(3),

                Repeater::make('list')
                    ->label('Liste')
                    ->schema([
                        Radio::make('icon')
                            ->label('Symbol')
                            ->options([
                                'none' => '❌ Keines',
                                'rufzeichen' => '❗ Rufzeichen',
                                'stern' => '⭐ Stern',
                                'arrow' => '➡️ Pfeil',
                            ])
                            ->inline()
                            ->required(),
                        Select::make('lang')
                            ->label('Sprache')
                            ->options([
                                'de' => 'Deutsch',
                                'en' => 'Englisch',
                            ])
                            ->default('de')
                            ->required(),
                        Repeater::make('list')
                            ->label('Listeneinträge')
                            ->simple(TextInput::make('list')),
                    ]),

            ])
            ->columns(1);
    }
}
