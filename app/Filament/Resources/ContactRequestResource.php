<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactRequestResource\Pages\ListContactRequests;
use App\Filament\Resources\ContactRequestResource\Pages\CreateContactRequest;
use App\Filament\Resources\ContactRequestResource\Pages\EditContactRequest;
use App\Filament\Resources\ContactRequestResource\Pages;
use App\Filament\Schemas\Resources\ContactRequestResource\Schemas\ContactRequestForm;
use App\Filament\Tables\Resources\ContactRequestResource\Schemas\ContactRequestTable;
use App\Models\ContactRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ContactRequestResource extends Resource
{
    protected static ?string $model = ContactRequest::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $label = 'Kontaktanfrage';

    protected static ?string $pluralLabel = 'Kontaktanfragen';

    protected static ?string $navigationLabel = 'Kontaktanfragen';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes();
    }

    public static function form(Schema $schema): Schema
    {
        return ContactRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContactRequestTable::configure($table);
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
            'index' => ListContactRequests::route('/'),
            'create' => CreateContactRequest::route('/create'),
            'edit' => EditContactRequest::route('/{record}/edit'),
        ];
    }
}
