<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactpersonResource\Pages;
use App\Filament\Schemas\Resources\ContactpersonResource\Schemas\ContactpersonForm;
use App\Filament\Tables\Resources\ContactpersonResource\Schemas\ContactpersonTable;
use App\Models\Contactperson;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ContactpersonResource extends Resource
{
    protected static ?string $model = Contactperson::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $label = 'Kontakt';

    protected static ?string $pluralLabel = 'Kontakte';

    protected static ?string $title = 'Kontakte';

    protected static \UnitEnum|string|null $navigationGroup = 'Zweisprachige Datenmodelle';

    public static function form(Schema $schema): Schema
    {
        return ContactpersonForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContactpersonTable::configure($table);
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
            'index' => Pages\ListContactpeople::route('/'),
            'create' => Pages\CreateContactperson::route('/create'),
            'edit' => Pages\EditContactperson::route('/{record}/edit'),
        ];
    }
}
