<?php

namespace App\Filament\Resources\DepartmentResource\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class SubRelationManager extends RelationManager
{
    protected static string $relationship = 'sub';

    protected static ?string $label = 'Unterabteilung';

    protected static ?string $pluralLabel = 'Unterabteilungen';

    protected static ?string $title = 'Unterabteilungen';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name.de')
                    ->label('Name Deutsch')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name.en')
                    ->label('Name Englisch')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('order')
                    ->required()
                    ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextInputColumn::make('name'),
                Tables\Columns\TextInputColumn::make('order')->rules(['required', 'numeric']),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
                AssociateAction::make(),
            ])
            ->actions([
                DeleteAction::make(),
                EditAction::make(),
            ])
            ->reorderable('order')
            ->modifyQueryUsing(fn ($query) => $query->withoutGlobalScopes(['main']));
    }
}
