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
                        TextInput::make('title')
                            ->label('Titel der Stellenanzeige')
                            ->required(),
                        RichEditor::make('description')
                            ->label('Beschreibung der Stellenanzeige')
                            ->required()
                            ->columnSpanFull(),
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

                        TextInput::make('job_title')
                            ->required()
                            ->label('Job Titel'),

                        Select::make('contract_type')
                            ->options([
                                'Freiberuflich' => 'Freiberuflich',
                                'Geringfügig' => 'Geringfügig',
                                'Teilzeit' => 'Teilzeit',
                                'Vollzeit' => 'Vollzeit',
                            ])
                            ->required(),

                        Select::make('unit_text')
                            ->label('Zahlungsinterval')
                            ->options([
                                'Stündlich' => 'Stündlich',
                                'Wöchentlich' => 'Wöchnentlich',
                                'Monatlich' => 'Monatlich',
                                'Jährlich' => 'Jährlich',
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
