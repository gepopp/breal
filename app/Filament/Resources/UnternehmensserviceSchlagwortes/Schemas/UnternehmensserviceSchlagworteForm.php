<?php

namespace App\Filament\Resources\UnternehmensserviceSchlagwortes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class UnternehmensserviceSchlagworteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name.de')
                    ->label('Bezeichnung Deutsch')
                    ->required()
                    ->columnSpanFull(),TextInput::make('name.en')
                    ->label('Bezeichnung Englisch')
                    ->required()
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }
}
