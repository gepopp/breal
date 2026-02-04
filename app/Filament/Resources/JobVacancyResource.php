<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobVacancyResource\Pages;
use App\Filament\Schemas\Resources\JobVacancyResource\Schemas\JobVacancyForm;
use App\Filament\Tables\Resources\JobVacancyResource\Schemas\JobVacancyTable;
use App\Models\JobVacancy;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class JobVacancyResource extends Resource
{
    protected static ?string $model = JobVacancy::class;

    protected static ?string $label = 'Stellenanzeige';

    protected static ?string $pluralLabel = 'Stellenanzeigen';

    protected static ?string $navigationLabel = 'Stellenanzeigen';

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-briefcase';

    public static function form(Schema $schema): Schema
    {
        return JobVacancyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JobVacancyTable::configure($table);
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
            'index' => Pages\ListJobVacancies::route('/'),
            'create' => Pages\CreateJobVacancy::route('/create'),
            'edit' => Pages\EditJobVacancy::route('/{record}/edit'),
        ];
    }
}
