<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RealtyResource\Pages\ListRealties;
use App\Filament\Resources\RealtyResource\Pages\CreateRealty;
use App\Filament\Resources\RealtyResource\Pages\EditRealty;
use App\Filament\Resources\RealtyResource\Pages;
use App\Filament\Schemas\Resources\RealtyResource\Schemas\RealtyForm;
use App\Filament\Tables\Resources\RealtyResource\Schemas\RealtyTable;
use App\Models\Realty;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class RealtyResource extends Resource
{
    protected static ?string $model = Realty::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Immobilien';

    protected static ?string $label = 'Immobilie';

    protected static ?string $pluralLabel = 'Immobilien';

    public static function form(Schema $schema): Schema
    {
        return RealtyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RealtyTable::configure($table);
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
            'index' => ListRealties::route('/'),
            'create' => CreateRealty::route('/create'),
            'edit' => EditRealty::route('/{record}/edit'),
        ];
    }
}
