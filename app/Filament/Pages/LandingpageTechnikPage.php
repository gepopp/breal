<?php

namespace App\Filament\Pages;

use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\ToggleButtons;
use App\Forms\Components\SettingsUpload;
use App\Settings\LandingpageTechnikSettings;
use BackedEnum;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class LandingpageTechnikPage extends SettingsPage
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected static string $settings = LandingpageTechnikSettings::class;

    protected static ?string $navigationLabel = 'Landingpage Technik';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Sektionen')
                    ->schema([
                        Tab::make('Intro')
                            ->schema([
                                SettingsUpload::make('hero_image')->disk('s3')->multiple()->reorderable()->panelLayout('grid')->required(),
                                TextInput::make('hero_image_alt')->required(),
                                TextInput::make('hero_header')->required(),
                                TextInput::make('hero_subheader')->required(),
                                RichEditor::make('hero_introtext')
                                    ->rules(['required', 'max:320', 'min:160'])
                                    ->required(),

                                ToggleButtons::make('intro_layout')
                                    ->grouped()
                                    ->reactive()
                                    ->options(['two_columns' => 'two_columns', 'text_image' => 'text_image']),

                                Section::make('two_columns')
                                    ->schema([
                                        RichEditor::make('hero_text_column_one')->required(),
                                        RichEditor::make('hero_text_column_two')->required(),
                                    ])
                                    ->visible(fn ($get) => $get('intro_layout') === 'two_columns'),

                                Section::make('text_image')
                                    ->schema([
                                        RichEditor::make('text')->required(),
                                        SettingsUpload::make('text_image')->disk('s3')->multiple()->reorderable()->panelLayout('grid')->required(),
                                        TextInput::make('text_image_alt')->required(),
                                    ])
                                    ->visible(fn ($get) => $get('intro_layout') === 'text_image'),

                            ]),
                        Tab::make('Über uns')
                            ->schema([
                                TextInput::make('about_header')->required(),
                                TextInput::make('about_subheader')->required(),
                                RichEditor::make('about_text')->required(),
                                SettingsUpload::make('about_image')->disk('s3')->required(),
                                TextInput::make('about_image_alt')->required(),
                                Textarea::make('about_video_embed_code'),
                            ]),
                    ]),
            ])->columns(1);
    }
}
