<?php

namespace App\Filament\Schemas\Resources\RealtyResource\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RealtyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('objektnr_intern'),
                TextInput::make('objektnr_extern'),
                TextInput::make('openimmo_obid'),
                TextInput::make('title'),
                Textarea::make('beschreibung')
                    ->columnSpanFull(),
                TextInput::make('zimmer')
                    ->numeric(),
                TextInput::make('wohnflaeche')
                    ->numeric(),
                TextInput::make('preis')
                    ->numeric(),
                TextInput::make('vermarktungsart')
                    ->required(),
                TextInput::make('nutzungsart'),
                TextInput::make('plz'),
                TextInput::make('ort'),
                TextInput::make('strasse'),
                TextInput::make('hausnummer'),
                TextInput::make('bundesland'),
                TextInput::make('wohnungsnummer'),
                TextInput::make('lat')
                    ->numeric(),
                TextInput::make('lng')
                    ->numeric(),
                TextInput::make('path'),
            ]);
    }
}
