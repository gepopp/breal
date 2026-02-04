<?php

namespace App\Filament\Schemas\Resources\ContactpersonResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class ContactpersonForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                SpatieMediaLibraryFileUpload::make('avatar')
                    ->maxSize(521)
                    ->collection('avatar')
                    ->responsiveImages()
                    ->rules(['nullable', 'image', 'max:521'])
                    ->downloadable()
                    ->image()
                    ->imageEditor()
                    ->label('Bild')
                    ->imageEditorAspectRatios(['1:1']),

                Select::make('department_id')
                    ->relationship('department', 'name', fn (Builder $query) => $query->withoutGlobalScopes())
                    ->searchable()
                    ->required(),
                TextInput::make('name')
                    ->columnSpanFull()
                    ->required(),
                TextInput::make('position.de')->label('Job Title Deutsch'),
                TextInput::make('position.en')->label('Job Title Englisch'),
                TextInput::make('email')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->required(),
            ])
            ->columns(2);
    }
}
