<?php

namespace App\Filament\Schemas\Resources\ReferenceResource\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ReferenceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                SpatieMediaLibraryFileUpload::make('titleimage')
                    ->label('Titelbild')
                    ->image()
                    ->required()
                    ->responsiveImages()
                    ->downloadable()
                    ->collection('titleimage'),

                Section::make('Texte')
                    ->schema([
                        TextInput::make('title.de')
                            ->label('Titel Deutsch')
                            ->required(),
                        TextInput::make('title.en')
                            ->label('Titel English')
                            ->required(),
                        Textarea::make('description.de')
                            ->label('Beschreibung Deutsch')
                            ->required(),
                        Textarea::make('description.en')
                            ->label('Beschreibund English')
                            ->required(),
                    ])
                    ->columns(2),

                Repeater::make('tags')
                    ->simple(TextInput::make('tag'))
                    ->required(),

                Section::make('Kunde')
                    ->schema([

                        SpatieMediaLibraryFileUpload::make('avatar')
                            ->image()
                            ->avatar()
                            ->columnSpan(1)
                            ->collection('avatar'),

                        Section::make()
                            ->schema([
                                TextInput::make('client_name')->label('Name des Kunden')->columnSpanFull(),
                                Textarea::make('testimonial.de')->label('Testimonial Deutsch'),
                                Textarea::make('testimonial.en')->label('Testimonial English'),
                            ])
                            ->columns(2)
                            ->columnSpan(4),

                    ])
                    ->columns(5),

            ])
            ->columns(1);
    }
}
