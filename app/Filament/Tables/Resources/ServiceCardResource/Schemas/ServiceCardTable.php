<?php

namespace App\Filament\Tables\Resources\ServiceCardResource\Schemas;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ServiceCardTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'service' => 'info',
                        'feature' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('icon'),
            ])
            ->reorderable('order')
            ->defaultSort('order')
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'service' => 'Service',
                        'feature' => 'Feature',
                    ]),
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
