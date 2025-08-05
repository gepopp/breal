<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactpersonResource\Pages;
use App\Filament\Resources\ContactpersonResource\RelationManagers;
use App\Models\Contactperson;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContactpersonResource extends Resource
{
    protected static ?string $model = Contactperson::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $label = 'Kontakt';

    protected static ?string $pluralLabel = 'Kontakte';


    protected static ?string $title = 'Kontakte';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\SpatieMediaLibraryFileUpload::make('avatar')
                    ->maxSize(521)
                    ->collection('avatar')
                    ->responsiveImages()
                    ->rules(['nullable', 'image', 'max:521'])
                    ->downloadable()
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios(['1:1']),


                Forms\Components\Select::make('department_id')
                    ->relationship('department', 'search_label', fn(Builder $query) => $query->withoutGlobalScopes())
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('position'),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->required(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('department.search_label')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('position')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
            ])
            ->filters([
                TrashedFilter::make('trashed'),
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
            'index'  => Pages\ListContactpeople::route('/'),
            'create' => Pages\CreateContactperson::route('/create'),
            'edit'   => Pages\EditContactperson::route('/{record}/edit'),
        ];
    }
}
