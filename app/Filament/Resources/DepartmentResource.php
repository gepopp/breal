<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartmentResource\Pages;
use App\Filament\Resources\DepartmentResource\RelationManagers;
use App\Filament\Resources\DepartmentResource\RelationManagers\ContactsRelationManager;
use App\Filament\Schemas\Resources\DepartmentResource\Schemas\DepartmentForm;
use App\Filament\Tables\Resources\DepartmentResource\Schemas\DepartmentTable;
use App\Models\Department;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $label = 'Abteilung';

    protected static ?string $pluralLabel = 'Abteilungen';

    protected static ?string $title = 'Ateilungen';

    protected static \UnitEnum|string|null $navigationGroup = 'Zweisprachige Datenmodelle';

    public static function form(Schema $schema): Schema
    {
        return DepartmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DepartmentTable::configure($table);
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
            'index' => Pages\ListDepartments::route('/'),
            'create' => Pages\CreateDepartment::route('/create'),
            'edit' => Pages\EditDepartment::route('/{record}/edit'),
        ];
    }
}
