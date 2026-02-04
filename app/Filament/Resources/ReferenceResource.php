<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReferenceResource\Pages;
use App\Filament\Schemas\Resources\ReferenceResource\Schemas\ReferenceForm;
use App\Filament\Tables\Resources\ReferenceResource\Schemas\ReferenceTable;
use App\Models\Reference;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ReferenceResource extends Resource
{
    protected static ?string $model = Reference::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-star';

    protected static \UnitEnum|string|null $navigationGroup = 'Zweisprachige Datenmodelle';

    public static function form(Schema $schema): Schema
    {
        return ReferenceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReferenceTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReferences::route('/'),
            'create' => Pages\CreateReference::route('/create'),
            'edit' => Pages\EditReference::route('/{record}/edit'),
        ];
    }
}
