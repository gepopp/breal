<?php

namespace App\Filament\Tables\Resources\ContactpersonResource\Schemas;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ContactpersonTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('department.name')
                    ->label('Abteilung')
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('position')
                    ->searchable(),
                TextColumn::make('email')
                    ->copyable()
                    ->searchable(),
                TextColumn::make('phone')
                    ->searchable(),
            ])
            ->filters([
                TrashedFilter::make('trashed'),
            ])
            ->actions([
                EditAction::make(),
                RestoreAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
