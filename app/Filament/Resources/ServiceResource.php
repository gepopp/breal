<?php

namespace App\Filament\Resources;

use UnitEnum;
use App\Filament\Resources\ServiceResource\Pages\ListServices;
use App\Filament\Resources\ServiceResource\Pages\CreateService;
use App\Filament\Resources\ServiceResource\Pages\EditService;
use App\Filament\Resources\ServiceResource\Pages;
use App\Filament\Schemas\Resources\ServiceResource\Schemas\ServiceForm;
use App\Filament\Tables\Resources\ServiceResource\Schemas\ServiceTable;
use App\Models\Service;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string | UnitEnum | null $navigationGroup = 'Zweisprachige Datenmodelle';

    public static function form(Schema $schema): Schema
    {
        return ServiceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServiceTable::configure($table);
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
            'index' => ListServices::route('/'),
            'create' => CreateService::route('/create'),
            'edit' => EditService::route('/{record}/edit'),
        ];
    }
}
