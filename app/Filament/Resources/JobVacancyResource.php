<?php

namespace App\Filament\Resources;

use App\Enums\CompaniesEnum;
use App\Filament\Resources\JobVacancyResource\Pages;
use App\Filament\Resources\JobVacancyResource\RelationManagers;
use App\Models\JobVacancy;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JobVacancyResource extends Resource
{
    protected static ?string $model = JobVacancy::class;

    protected static ?string $label = 'Stellenanzeige';
    protected static ?string $pluralLabel = 'Stellenanzeigen';

    protected static ?string $navigationLabel = 'Stellenanzeigen';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make()
                    ->schema([

                        Forms\Components\SpatieMediaLibraryFileUpload::make('titleimage')
                            ->collection('title')
                            ->rule(['required', 'max:1024', 'mimes:jpg,jpeg,png'])
                            ->hint('jpg, jpeg, png mit max. 1MB')
                            ->downloadable()
                            ->image()
                            ->required(),

                        Forms\Components\Select::make('company')
                            ->options(CompaniesEnum::class)
                            ->required(),
                        Forms\Components\TextInput::make('title')
                            ->label('Titel der Stellenanzeige')
                            ->required(),
                        Forms\Components\RichEditor::make('description')
                            ->label('Beschreibung der Stellenanzeige')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('email')
                            ->label('Bewerbungen an')
                            ->email()
                            ->required(),

                    ])->columnSpan(3),

                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\DateTimePicker::make('from')
                            ->label('Gültig von')
                            ->required(),
                        Forms\Components\DateTimePicker::make('to')
                            ->label('Gültig bis')
                            ->required(),

                        Forms\Components\TextInput::make('job_title')
                            ->required()
                            ->label('Job Titel'),

                        Forms\Components\Select::make('contract_type')
                            ->options([
                                'Freiberuflich' => 'Freiberuflich',
                                'Geringfügig'   => 'Geringfügig',
                                'Teilzeit'      => 'Teilzeit',
                                'Vollzeit'      => 'Vollzeit',
                            ])
                            ->required(),

                        Forms\Components\Select::make('unit_text')
                            ->label('Zahlungsinterval')
                            ->options([
                                'Stündlich'   => 'Stündlich',
                                'Wöchentlich' => 'Wöchnentlich',
                                'Monatlich'   => 'Monatlich',
                                'Jährlich'    => 'Jährlich',
                            ]),

                        Forms\Components\TextInput::make('salary')
                            ->label('Bezahlung')
                            ->required()
                            ->numeric()
                            ->default(0),


                    ])->columnSpan(1)


            ])->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('title')
                    ->searchable(),

                Tables\Columns\TextColumn::make('from')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('to')
                    ->dateTime()
                    ->sortable(),
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
            'index'  => Pages\ListJobVacancies::route('/'),
            'create' => Pages\CreateJobVacancy::route('/create'),
            'edit'   => Pages\EditJobVacancy::route('/{record}/edit'),
        ];
    }
}
