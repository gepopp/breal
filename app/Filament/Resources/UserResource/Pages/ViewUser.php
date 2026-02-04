<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Nutzerdetails')
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->disabled(),
                        TextInput::make('email')
                            ->label('E-Mail')
                            ->disabled(),
                        Toggle::make('admin')
                            ->label('Administrator')
                            ->disabled(),
                        Placeholder::make('created_at')
                            ->label('Erstellt am')
                            ->content(fn ($record) => $record->created_at?->format('d.m.Y H:i:s')),
                        Placeholder::make('updated_at')
                            ->label('Aktualisiert am')
                            ->content(fn ($record) => $record->updated_at?->format('d.m.Y H:i:s')),
                    ]),
            ])
            ->columns(1);
    }

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
