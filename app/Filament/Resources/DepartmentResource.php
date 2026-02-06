<?php

namespace App\Filament\Resources;

use UnitEnum;
use App\Filament\Resources\DepartmentResource\RelationManagers\SubRelationManager;
use App\Filament\Resources\DepartmentResource\Pages\ListDepartments;
use App\Filament\Resources\DepartmentResource\Pages\CreateDepartment;
use App\Filament\Resources\DepartmentResource\Pages\EditDepartment;
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

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $label = 'Abteilung';

    protected static ?string $pluralLabel = 'Abteilungen';

    protected static ?string $title = 'Ateilungen';

    protected static string | UnitEnum | null $navigationGroup = 'Zweisprachige Datenmodelle';

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
            SubRelationManager::class,
            ContactsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDepartments::route('/'),
            'create' => CreateDepartment::route('/create'),
            'edit' => EditDepartment::route('/{record}/edit'),
        ];
    }
}
