<?php

namespace App\Filament\Tables\Resources\QuestionResource\Schemas;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class QuestionTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->dateTime('d.m.Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('company')
                    ->searchable(),
                TextColumn::make('question')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('link')
                    ->wrap()
                    ->copyable(),
            ])
            ->reorderable('order')
            ->defaultSort('order')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
