<?php

namespace App\Filament\Resources;

use UnitEnum;
use App\Filament\Resources\ReferenceResource\Pages\ListReferences;
use App\Filament\Resources\ReferenceResource\Pages\CreateReference;
use App\Filament\Resources\ReferenceResource\Pages\EditReference;
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

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-star';

    protected static string | UnitEnum | null $navigationGroup = 'Zweisprachige Datenmodelle';

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
            'index' => ListReferences::route('/'),
            'create' => CreateReference::route('/create'),
            'edit' => EditReference::route('/{record}/edit'),
        ];
    }
}
