<?php

namespace App\Filament\Resources;

use App\Enums\CompaniesEnum;
use App\Filament\Resources\ServiceResource\Pages;
use App\Filament\Resources\ServiceResource\RelationManagers;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order')
                    ->required()
                    ->numeric(),
                Forms\Components\Toggle::make('on_landing')
                    ->label('Auf Startseite anzeigen')
                    ->default(true)
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->label('Bezeichnung auf der Startseite')
                    ->required(),
                Forms\Components\Repeater::make('points')
                    ->label('Liste auf der Startseite')
                    ->simple(Forms\Components\TextInput::make('point')),
                Forms\Components\Select::make('company')
                    ->options(CompaniesEnum::class),

                Forms\Components\RichEditor::make('description'),

                Forms\Components\Repeater::make('links')
                    ->schema([
                        FileUpload::make('path')
                            ->required()
                            ->downloadable()
                            ->preserveFilenames()
                            ->acceptedFileTypes(['application/pdf'])
                            ->label('Dokument'),

                        Forms\Components\TextInput::make('name')
                            ->required()

                    ]),

                Forms\Components\Repeater::make('list')
                    ->schema([
                        Forms\Components\Select::make('icon')
                            ->options([
                                'none'       => 'Keines',
                                'rufzeichen' => 'Rufzeichen',
                                'stern'      => 'Stern',
                                'arrow'      => 'Pfeil',
                            ]),
                        Forms\Components\Repeater::make('list')
                            ->simple(TextInput::make('list'))
                    ])


            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\IconColumn::make('on_landing')
                    ->boolean(),
                //                Tables\Columns\TextColumn::make('title')
                //                    ->searchable(),
                //                Tables\Columns\TextColumn::make('description')
                //                    ->searchable(),
                //                Tables\Columns\TextColumn::make('created_at')
                //                    ->dateTime()
                //                    ->sortable()
                //                    ->toggleable(isToggledHiddenByDefault: true),
                //                Tables\Columns\TextColumn::make('updated_at')
                //                    ->dateTime()
                //                    ->sortable()
                //                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index'  => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit'   => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
