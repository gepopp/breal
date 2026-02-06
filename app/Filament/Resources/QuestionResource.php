<?php

namespace App\Filament\Resources;

use UnitEnum;
use App\Filament\Resources\QuestionResource\Pages\ListQuestions;
use App\Filament\Resources\QuestionResource\Pages\CreateQuestion;
use App\Filament\Resources\QuestionResource\Pages\EditQuestion;
use App\Filament\Resources\QuestionResource\Pages;
use App\Filament\Schemas\Resources\QuestionResource\Schemas\QuestionForm;
use App\Filament\Tables\Resources\QuestionResource\Schemas\QuestionTable;
use App\Models\Question;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static ?string $label = 'FAQ Frage';

    protected static ?string $pluralLabel = 'FAQ Fragen';

    protected static ?string $navigationLabel = 'FAQ Fragen';

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static string | UnitEnum | null $navigationGroup = 'Zweisprachige Datenmodelle';

    public static function form(Schema $schema): Schema
    {
        return QuestionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QuestionTable::configure($table);
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
            'index' => ListQuestions::route('/'),
            'create' => CreateQuestion::route('/create'),
            'edit' => EditQuestion::route('/{record}/edit'),
        ];
    }
}
