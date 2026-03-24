<?php

namespace App\Filament\Schemas\Resources\TimelineResource\Schemas;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TimelineForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Bild und Jahr')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('titleimage')
                            ->label('Titelbild')
                            ->required()
                            ->disk('hetzner')
                            ->image()
                            ->downloadable()
                            ->collection('titleimage'),
                        TextInput::make('year')
                            ->label('Jahr')
                            ->required()
                            ->numeric(),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),
                Section::make('Inhalte')
                    ->schema([
                        TextInput::make('title.de')
                            ->label('Titel (Deutsch)')
                            ->required(),
                        TextInput::make('title.en')
                            ->label('Titel (Englisch)')
                            ->required(),
                        Textarea::make('description.de')
                            ->label('Beschreibung (Deutsch)')
                            ->required()
                            ->rows(8),
                        Textarea::make('description.en')
                            ->label('Beschreibung (Englisch)')
                            ->required()
                            ->rows(8),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
