<?php

namespace App\Filament\Tables\Resources\RealtyResource\Schemas;

use App\Justimmo\Importer;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RealtyTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('objektnr_intern')
                    ->searchable(),
                TextColumn::make('objektnr_extern')
                    ->searchable(),
                TextColumn::make('openimmo_obid')
                    ->searchable(),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('zimmer')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('wohnflaeche')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('preis')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('vermarktungsart')
                    ->searchable(),
                TextColumn::make('nutzungsart')
                    ->searchable(),
                TextColumn::make('plz')
                    ->searchable(),
                TextColumn::make('ort')
                    ->searchable(),
                TextColumn::make('strasse')
                    ->searchable(),
                TextColumn::make('hausnummer')
                    ->searchable(),
                TextColumn::make('bundesland')
                    ->searchable(),
                TextColumn::make('wohnungsnummer')
                    ->searchable(),
                TextColumn::make('lat')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('lng')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('path')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('import')
                    ->action(function () {
                        Importer::import();
                    }),
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
