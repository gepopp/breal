<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RealtyResource\Pages;
use App\Filament\Resources\RealtyResource\RelationManagers;
use App\Justimmo\Importer;
use App\Models\Realty;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RealtyResource extends Resource
{
    protected static ?string $model = Realty::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Immobilien';
    protected static ?string $label = 'Immobilie';
    protected static ?string $pluralLabel = 'Immobilien';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('objektnr_intern'),
                Forms\Components\TextInput::make('objektnr_extern'),
                Forms\Components\TextInput::make('openimmo_obid'),
                Forms\Components\TextInput::make('title'),
                Forms\Components\Textarea::make('beschreibung')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('zimmer')
                    ->numeric(),
                Forms\Components\TextInput::make('wohnflaeche')
                    ->numeric(),
                Forms\Components\TextInput::make('preis')
                    ->numeric(),
                Forms\Components\TextInput::make('vermarktungsart')
                    ->required(),
                Forms\Components\TextInput::make('nutzungsart'),
                Forms\Components\TextInput::make('plz'),
                Forms\Components\TextInput::make('ort'),
                Forms\Components\TextInput::make('strasse'),
                Forms\Components\TextInput::make('hausnummer'),
                Forms\Components\TextInput::make('bundesland'),
                Forms\Components\TextInput::make('wohnungsnummer'),
                Forms\Components\TextInput::make('lat')
                    ->numeric(),
                Forms\Components\TextInput::make('lng')
                    ->numeric(),
                Forms\Components\TextInput::make('path'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('objektnr_intern')
                    ->searchable(),
                Tables\Columns\TextColumn::make('objektnr_extern')
                    ->searchable(),
                Tables\Columns\TextColumn::make('openimmo_obid')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('zimmer')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('wohnflaeche')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('preis')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vermarktungsart')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nutzungsart')
                    ->searchable(),
                Tables\Columns\TextColumn::make('plz')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ort')
                    ->searchable(),
                Tables\Columns\TextColumn::make('strasse')
                    ->searchable(),
                Tables\Columns\TextColumn::make('hausnummer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bundesland')
                    ->searchable(),
                Tables\Columns\TextColumn::make('wohnungsnummer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lat')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lng')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('path')
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
            ->headerActions([
                Tables\Actions\Action::make('import')
                ->action(function () {
                    Importer::import();
                })
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
            'index' => Pages\ListRealties::route('/'),
            'create' => Pages\CreateRealty::route('/create'),
            'edit' => Pages\EditRealty::route('/{record}/edit'),
        ];
    }
}
