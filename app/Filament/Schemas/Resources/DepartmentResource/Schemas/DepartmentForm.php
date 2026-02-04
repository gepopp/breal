<?php

namespace App\Filament\Schemas\Resources\DepartmentResource\Schemas;

use App\Enums\CompaniesEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DepartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('company')
                    ->label('Unternehmen')
                    ->options(CompaniesEnum::class)
                    ->columnSpanFull()
                    ->searchable(),

                TextInput::make('name.de')
                    ->label('Name Deutsch')
                    ->required(),

                TextInput::make('name.en')
                    ->label('Name Englisch')
                    ->required(),

            ])
            ->columns(2);
    }
}
