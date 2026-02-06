<?php

namespace App\Filament\Tables\Resources\DepartmentResource\Schemas;

use App\Enums\CompaniesEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DepartmentTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company')
                    ->label('Unternehmen')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('main.name')
                    ->label('Hauptabteilung')
                    ->sortable(),

                TextColumn::make('name')
                    ->sortable(),
            ])
            ->reorderable('order')
            ->defaultSort('order')
            ->filters([
                SelectFilter::make('company')
                    ->options(CompaniesEnum::class),
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
