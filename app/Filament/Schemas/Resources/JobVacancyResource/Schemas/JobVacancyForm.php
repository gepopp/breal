<?php

namespace App\Filament\Schemas\Resources\JobVacancyResource\Schemas;

use App\Enums\CompaniesEnum;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class JobVacancyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make()
                    ->schema([

                        SpatieMediaLibraryFileUpload::make('titleimage')
                            ->collection('title')
                            ->rule(['required', 'max:1024', 'mimes:jpg,jpeg,png'])
                            ->hint('jpg, jpeg, png mit max. 1MB')
                            ->downloadable()
                            ->image()
                            ->required(),

                        Select::make('company')
                            ->options(CompaniesEnum::class)
                            ->required(),

                        Section::make()
                            ->schema([
                                TextInput::make('title.de')
                                    ->label('Titel der Stellenanzeige Deutsch')
                                    ->required(),
                                TextInput::make('title.en')
                                    ->label('Titel der Stellenanzeige Englisch')
                                    ->required(),

                                RichEditor::make('description.de')
                                    ->label('Beschreibung der Stellenanzeige Deutsch')
                                    ->required()
                                    ->columnSpanFull(),
                                RichEditor::make('description.en')
                                    ->label('Beschreibung der Stellenanzeige Englisch')
                                    ->required()
                                    ->columnSpanFull(),
                            ])->columns(2),


                        TextInput::make('email')
                            ->label('Bewerbungen an')
                            ->email()
                            ->required(),

                    ])->columnSpan(3),

                Section::make()
                    ->schema([
                        DateTimePicker::make('from')
                            ->label('Gültig von')
                            ->required(),
                        DateTimePicker::make('to')
                            ->label('Gültig bis')
                            ->required(),

                        TextInput::make('job_title.de')
                            ->required()
                            ->label('Job Titel Deutsch'),

                        TextInput::make('job_title.en')
                            ->required()
                            ->label('Job Titel Englisch'),

                        Select::make('contract_type')
                            ->label('Anstellungsart')
                            ->options([
                                'Freiberuflich' => 'Freiberuflich',
                                'Geringfügig'   => 'Geringfügig',
                                'Teilzeit'      => 'Teilzeit',
                                'Vollzeit'      => 'Vollzeit',
                            ])
                            ->required(),

                        Select::make('unit_text')
                            ->label('Zahlungsintervall')
                            ->options([
                                'Stündlich'   => 'Stündlich',
                                'Wöchentlich' => 'Wöchnentlich',
                                'Monatlich'   => 'Monatlich',
                                'Jährlich'    => 'Jährlich',
                            ]),

                        TextInput::make('salary')
                            ->label('Bezahlung')
                            ->required()
                            ->numeric()
                            ->default(0),

                    ])->columnSpan(1),

            ])
            ->columns(4);
    }
}
