<?php

namespace App\Filament\Schemas\Resources\QuestionResource\Schemas;

use App\Enums\CompaniesEnum;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class QuestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                SpatieMediaLibraryFileUpload::make('image')
                    ->image()
                    ->collection('image')
                    ->disk('s3')
                    ->downloadable()
                    ->responsiveImages()
                    ->rules(['nullable', 'image', 'max:2024'])
                    ->preserveFilenames(),

                Select::make('company')
                    ->options(CompaniesEnum::class),

                Section::make()
                    ->schema([
                        TextInput::make('question.de')
                            ->label('Frage Deutsch')
                            ->required(),
                        TextInput::make('question.en')
                            ->label('Frage Englisch')

                            ->required(),
                        Textarea::make('excerpt.de')
                            ->label('Auszug Deutsch')
                            ->required(),
                        Textarea::make('excerpt.en')
                            ->label('Auszug English')
                            ->required(),
                        RichEditor::make('answer.de')
                            ->label('Antwort Deutsch')
                            ->required(),
                        RichEditor::make('answer.en')
                            ->label('Antwort Englisch')
                            ->required(),
                    ])->columns(2),

            ])
            ->columns(1);
    }
}
