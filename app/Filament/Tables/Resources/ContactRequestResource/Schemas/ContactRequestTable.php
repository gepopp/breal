<?php

namespace App\Filament\Tables\Resources\ContactRequestResource\Schemas;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ContactRequestTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('id'),
                TextColumn::make('firstname')
                    ->searchable(),
                TextColumn::make('lastname')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('phone')
                    ->searchable(),

                TextColumn::make('address')
                    ->wrap()
                    ->searchable(),

                TextColumn::make('media_count')->counts('media'),

                TextColumn::make('solved_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('verified_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make('trashed'),
                TernaryFilter::make('verified')
                    ->label('Verifizierte Anfragen')
                    ->nullable()
                    ->queries(
                        blank: fn ($query) => $query->whereNotNull('verified_at'),
                        true: fn ($query) => $query,
                        false: fn ($query) => $query->whereNull('verified_at'),
                    )
                    ->placeholder('Ohne unverifizierte Anfragen')
                    ->trueLabel('Mit unverifizierten Anfragen')
                    ->falseLabel('Nur unverifizierte Anfragen')
                    ->baseQuery(fn (Builder $query) => $query->withoutGlobalScopes(['verified'])),

                TernaryFilter::make('solved')
                    ->label('Bearbeitete Anfragen')
                    ->nullable()
                    ->queries(
                        blank: fn ($query) => $query->whereNull('solved_at'),
                        true: fn ($query) => $query,
                        false: fn ($query) => $query->whereNotNull('solved_at'),
                    )
                    ->placeholder('Ohne bearbeitete Anfragen')
                    ->trueLabel('Mit bearbeiteten Anfragen')
                    ->falseLabel('Nur bearbeitete Anfragen')
                    ->baseQuery(fn (Builder $query) => $query->withoutGlobalScopes(['solved'])),

            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
