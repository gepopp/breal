<?php

namespace App\Filament\Resources\UnternehmensserviceSchlagwortes;

use App\Filament\Resources\UnternehmensserviceSchlagwortes\Pages\CreateUnternehmensserviceSchlagworte;
use App\Filament\Resources\UnternehmensserviceSchlagwortes\Pages\EditUnternehmensserviceSchlagworte;
use App\Filament\Resources\UnternehmensserviceSchlagwortes\Pages\ListUnternehmensserviceSchlagwortes;
use App\Filament\Resources\UnternehmensserviceSchlagwortes\Pages\ViewUnternehmensserviceSchlagworte;
use App\Filament\Resources\UnternehmensserviceSchlagwortes\Schemas\UnternehmensserviceSchlagworteForm;
use App\Filament\Resources\UnternehmensserviceSchlagwortes\Schemas\UnternehmensserviceSchlagworteInfolist;
use App\Filament\Resources\UnternehmensserviceSchlagwortes\Tables\UnternehmensserviceSchlagwortesTable;
use App\Models\UnternehmensserviceSchlagworte;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class UnternehmensserviceSchlagworteResource extends Resource
{
    protected static ?string $model = UnternehmensserviceSchlagworte::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Tag;

    protected static string|UnitEnum|null $navigationGroup = 'Zweisprachige Datenmodelle';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return UnternehmensserviceSchlagworteForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return UnternehmensserviceSchlagworteInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UnternehmensserviceSchlagwortesTable::configure($table);
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
            'index' => ListUnternehmensserviceSchlagwortes::route('/'),
            'create' => CreateUnternehmensserviceSchlagworte::route('/create'),
            'edit' => EditUnternehmensserviceSchlagworte::route('/{record}/edit'),
        ];
    }
}
