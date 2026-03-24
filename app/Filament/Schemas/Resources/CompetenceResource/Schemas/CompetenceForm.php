<?php

namespace App\Filament\Schemas\Resources\CompetenceResource\Schemas;

use App\Enums\CompaniesEnum;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CompetenceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                SpatieMediaLibraryFileUpload::make('titleimage')
                    ->image()
                    ->disk('hetzner')
                    ->collection('titleimage')
                    ->downloadable()
                    ->responsiveImages()
                    ->preserveFilenames()
                    ->required(),

                Select::make('company')
                    ->options(CompaniesEnum::class)
                    ->required(),

                TextInput::make('keyword')
                    ->required(),

                Section::make()
                    ->schema([

                        TextInput::make('name.de')
                            ->label('Bezeichnung Deutsch')
                            ->required(),
                        TextInput::make('name.en')
                            ->label('Bezeichnung Englisch')
                            ->required(),

                        Textarea::make('description.de')
                            ->label('Introtext Deutsch')
                            ->required()
                            ->columnSpanFull(),
                        Textarea::make('description.en')
                            ->label('Introtext Englisch')
                            ->required()
                            ->columnSpanFull(),

                        RichEditor::make('body.de')
                            ->label('Langtext Deutsch')
                            ->required(),
                        RichEditor::make('body.en')
                            ->label('Langtext Englisch')
                            ->required(),
                    ])->columns(2),






                Toggle::make('on_landing')->label('Auf der Landingpage anzeigen'),
                Toggle::make('on_dropdown')->label('Im Dropdown Menü anzeigen'),

            ])
            ->columns(1);
    }
}
