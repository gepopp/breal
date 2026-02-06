<?php

namespace App\Filament\Resources;

use UnitEnum;
use App\Filament\Resources\TimelineResource\Pages\ListTimelines;
use App\Filament\Resources\TimelineResource\Pages\CreateTimeline;
use App\Filament\Resources\TimelineResource\Pages\EditTimeline;
use App\Filament\Resources\TimelineResource\Pages;
use App\Filament\Schemas\Resources\TimelineResource\Schemas\TimelineForm;
use App\Filament\Tables\Resources\TimelineResource\Schemas\TimelineTable;
use App\Models\Timeline;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class TimelineResource extends Resource
{
    protected static ?string $model = Timeline::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-clock';

    protected static string | UnitEnum | null $navigationGroup = 'Zweisprachige Datenmodelle';

    public static function form(Schema $schema): Schema
    {
        return TimelineForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TimelineTable::configure($table);
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
            'index' => ListTimelines::route('/'),
            'create' => CreateTimeline::route('/create'),
            'edit' => EditTimeline::route('/{record}/edit'),
        ];
    }
}
