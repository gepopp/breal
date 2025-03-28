<?php

namespace App\Filament\Resources;

use App\Enums\CompaniesEnum;
use App\Filament\Resources\ContactRequestResource\Pages;
use App\Filament\Resources\ContactRequestResource\RelationManagers;
use App\Models\ContactRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContactRequestResource extends Resource
{
    protected static ?string $model = ContactRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $label = 'Kontaktanfrage';

    protected static ?string $pluralLabel = 'Kontaktanfragen';

    protected static ?string $navigationLabel = 'Kontaktanfragen';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('firstname')
                    ->required(),
                Forms\Components\TextInput::make('lastname')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->required(),
                Forms\Components\TextInput::make('message')
                    ->required(),
                Forms\Components\Select::make('company')
                    ->options(CompaniesEnum::class),
                Forms\Components\DateTimePicker::make('solved_at'),
                Forms\Components\DateTimePicker::make('verified_at')->readonly(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                Tables\Columns\TextColumn::make('firstname')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lastname')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('message')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('solved_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('verified_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make('trashed'),
                Tables\Filters\TernaryFilter::make('verified')
                    ->label('Verifizierte Anfragen')
                    ->nullable()
                    ->queries(
                        blank: fn($query) => $query->whereNotNull('verified_at'),
                        true: fn($query) => $query,
                        false: fn($query) => $query->whereNull('verified_at'),
                    )
                    ->placeholder('Ohne unverifizierte Anfragen')
                    ->trueLabel('Mit unverifizierten Anfragen')
                    ->falseLabel('Nur unverifizierte Anfragen')
                    ->baseQuery(fn(Builder $query) => $query->withoutGlobalScopes(['verified'])),

                Tables\Filters\TernaryFilter::make('solved')
                    ->label('Bearbeitete Anfragen')
                    ->nullable()
                    ->queries(
                        blank: fn($query) => $query->whereNull('solved_at'),
                        true: fn($query) => $query,
                        false: fn($query) => $query->whereNotNull('solved_at'),
                    )
                    ->placeholder('Ohne bearbeitete Anfragen')
                    ->trueLabel('Mit bearbeiteten Anfragen')
                    ->falseLabel('Nur bearbeitete Anfragen')
                    ->baseQuery(fn(Builder $query) => $query->withoutGlobalScopes(['solved'])),

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
            'index'  => Pages\ListContactRequests::route('/'),
            'create' => Pages\CreateContactRequest::route('/create'),
            'edit'   => Pages\EditContactRequest::route('/{record}/edit'),
        ];
    }
}
