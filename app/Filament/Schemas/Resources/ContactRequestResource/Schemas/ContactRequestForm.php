<?php

namespace App\Filament\Schemas\Resources\ContactRequestResource\Schemas;

use App\Enums\CompaniesEnum;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ContactRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('firstname')
                    ->required(),
                TextInput::make('lastname')
                    ->required(),
                TextInput::make('email')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->required(),
                TextInput::make('address')
                    ->tel()
                    ->required(),
                RichEditor::make('message')
                    ->required(),
                Select::make('company')
                    ->options(CompaniesEnum::class),

                DateTimePicker::make('solved_at'),
                DateTimePicker::make('verified_at')->readonly(),

                SpatieMediaLibraryFileUpload::make('uploads')
                    ->multiple()
                    ->disk('hetzner')
                    ->downloadable()
                    ->disk('local')
                    ->visibility('private')
                    ->collection('uploads'),

            ])
            ->columns(1);
    }
}
