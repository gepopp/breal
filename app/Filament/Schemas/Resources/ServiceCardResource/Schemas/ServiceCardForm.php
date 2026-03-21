<?php

namespace App\Filament\Schemas\Resources\ServiceCardResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\HtmlString;

class ServiceCardForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->label('Type')
                    ->options([
                        'service' => 'Service',
                        'feature' => 'Feature',
                    ])
                    ->required()
                    ->columnSpanFull(),

                Select::make('icon')
                    ->label('Icon')
                    ->options(self::getHeroiconOptions())
                    ->searchable()
                    ->optionsLimit(1000)
                    ->noSearchResultsMessage('No icons found.')
                    ->searchPrompt('Search icons...')
                    ->required()
                    ->allowHtml()
                    ->hint(new HtmlString('<a href="https://heroicons.dev" target="_blank" class="text-primary-600 hover:underline">Browse Heroicons</a>'))
                    ->columnSpanFull(),

                Grid::make(2)->schema([
                    TextInput::make('name.de')
                        ->label('Name (Deutsch)')
                        ->required(),
                    TextInput::make('name.en')
                        ->label('Name (English)')
                        ->required(),
                ]),

                Grid::make(2)->schema([
                    Textarea::make('text.de')
                        ->label('Text (Deutsch)')
                        ->required(),
                    Textarea::make('text.en')
                        ->label('Text (English)')
                        ->required(),
                ]),
            ])
            ->columns(1);
    }

    protected static function getHeroiconOptions(): array
    {
        return collect(Heroicon::cases())
            ->filter(fn ($icon) => ! str_starts_with($icon->name, 'Outlined'))
            ->mapWithKeys(function ($icon) {
                $iconHtml = svg('heroicon-o-'.$icon->value, 'w-5 h-5')->toHtml();
                $label = '<div class="flex items-center gap-2">'.$iconHtml.'<span>'.$icon->name.'</span></div>';

                return [$icon->value => $label];
            })
            ->toArray();
    }
}
