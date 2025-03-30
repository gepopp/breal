<?php

namespace App\Filament\Resources;

use App\Enums\CompaniesEnum;
use App\Filament\Resources\DepartmentResource\Pages;
use App\Filament\Resources\DepartmentResource\RelationManagers;
use App\Filament\Resources\DepartmentResource\RelationManagers\ContactsRelationManager;
use App\Models\Department;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    protected static ?string $label = 'Abteilung';

    protected static ?string $pluralLabel = 'Abteilungen';


    protected static ?string $title = 'Ateilungen';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('company')
                    ->label('Unternehmen')
                    ->options(CompaniesEnum::class)
                    ->searchable(),


                Forms\Components\TextInput::make('name')
                    ->required(),

                Forms\Components\TextInput::make('order')
                    ->numeric()
                    ->nullable()


            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company')
                    ->label('Unternehmen')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('main.name')
                    ->label('Hauptabteilung')
                    ->sortable(),

                Tables\Columns\TextColumn::make('name'),

                Tables\Columns\TextInputColumn::make('order')->rules(['numeric', 'integer', 'nullable'])
            ])
            ->filters([
                SelectFilter::make('company')
                    ->options(CompaniesEnum::class),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\SubRelationManager::class,
            ContactsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListDepartments::route('/'),
            'create' => Pages\CreateDepartment::route('/create'),
            'edit'   => Pages\EditDepartment::route('/{record}/edit'),
        ];
    }
}
