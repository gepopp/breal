<?php

namespace App\Filament\Resources;

use App\Enums\CompaniesEnum;
use App\Filament\Resources\CompetenceResource\Pages;
use App\Filament\Resources\CompetenceResource\RelationManagers;
use App\Models\Competence;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompetenceResource extends Resource
{
    protected static ?string $model = Competence::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Leistungen';
    protected static ?string $label = 'Leistung';
    protected static ?string $pluralLabel = 'Leistungen';
    protected static ?string $title = 'Leistungen';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\SpatieMediaLibraryFileUpload::make('titleimage')
                    ->image()
                    ->collection('titleimage')
                    ->downloadable()
                    ->responsiveImages()
                    ->preserveFilenames()
                    ->required(),


                Forms\Components\TextInput::make('order')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('company')
                    ->options(CompaniesEnum::class)
                    ->required(),
                Forms\Components\TextInput::make('keyword')
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label('Introtext')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('body')
                    ->label('Langtext')
                    ->required()
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('company')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index'  => Pages\ListCompetences::route('/'),
            'create' => Pages\CreateCompetence::route('/create'),
            'edit'   => Pages\EditCompetence::route('/{record}/edit'),
        ];
    }
}
