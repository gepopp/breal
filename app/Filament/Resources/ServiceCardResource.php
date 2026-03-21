<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceCardResource\Pages\CreateServiceCard;
use App\Filament\Resources\ServiceCardResource\Pages\EditServiceCard;
use App\Filament\Resources\ServiceCardResource\Pages\ListServiceCards;
use App\Filament\Schemas\Resources\ServiceCardResource\Schemas\ServiceCardForm;
use App\Filament\Tables\Resources\ServiceCardResource\Schemas\ServiceCardTable;
use App\Models\ServiceCard;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class ServiceCardResource extends Resource
{
    protected static ?string $model = ServiceCard::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string|UnitEnum|null $navigationGroup = 'Zweisprachige Datenmodelle';

    protected static ?string $navigationLabel = 'Unternehmensservice Cards';

    public static function form(Schema $schema): Schema
    {
        return ServiceCardForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServiceCardTable::configure($table);
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
            'index' => ListServiceCards::route('/'),
            'create' => CreateServiceCard::route('/create'),
            'edit' => EditServiceCard::route('/{record}/edit'),
        ];
    }
}
