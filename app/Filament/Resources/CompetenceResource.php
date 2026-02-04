<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompetenceResource\Pages;
use App\Filament\Schemas\Resources\CompetenceResource\Schemas\CompetenceForm;
use App\Filament\Tables\Resources\CompetenceResource\Schemas\CompetenceTable;
use App\Models\Competence;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class CompetenceResource extends Resource
{
    protected static ?string $model = Competence::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationLabel = 'Leistungen';

    protected static ?string $label = 'Leistung';

    protected static ?string $pluralLabel = 'Leistungen';

    protected static ?string $title = 'Leistungen';

    protected static \UnitEnum|string|null $navigationGroup = 'Zweisprachige Datenmodelle';

    public static function form(Schema $schema): Schema
    {
        return CompetenceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompetenceTable::configure($table);
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
            'index' => Pages\ListCompetences::route('/'),
            'create' => Pages\CreateCompetence::route('/create'),
            'edit' => Pages\EditCompetence::route('/{record}/edit'),
        ];
    }
}
