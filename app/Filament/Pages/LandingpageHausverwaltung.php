<?php

namespace App\Filament\Pages;

use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\ToggleButtons;
use App\Forms\Components\SettingsUpload;
use App\Settings\LandingpageHausverwaltung as Setting;
use BackedEnum;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class LandingpageHausverwaltung extends SettingsPage
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected static string $settings = Setting::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Sektionen')
                    ->schema([
                        Tab::make('Intro')
                            ->schema([
                                SettingsUpload::make('hero_image')->multiple()->reorderable()->panelLayout('grid')->required(),
                                TextInput::make('hero_image_alt')->required(),
                                TextInput::make('hero_speed')->numeric()->required(),
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
                                        SettingsUpload::make('text_image')->multiple()->reorderable()->panelLayout('grid')->required(),
                                        TextInput::make('text_image_alt')->required(),
                                    ])
                                    ->visible(fn ($get) => $get('intro_layout') === 'text_image'),

                            ]),
                        Tab::make('Über uns')
                            ->schema([
                                TextInput::make('about_header')->required(),
                                TextInput::make('about_subheader')->required(),
                                RichEditor::make('about_text')->required(),
                                SettingsUpload::make('about_image')->required(),
                                TextInput::make('about_image_alt')->required(),
                                Textarea::make('about_video_embed_code'),
                            ]),
                        Tab::make('Timeline')
                            ->schema([
                                TextInput::make('timeline_header')->required(),
                                TextInput::make('timeline_subheader')->required(),
                                RichEditor::make('timeline_intro')->required(),
                                TextInput::make('timeline_speed')->numeric()->required(),
                            ]),
                        Tab::make('Service')
                            ->schema([
                                TextInput::make('service_heading')->required(),
                                TextInput::make('service_subheading')->required(),
                                RichEditor::make('service_introtext')->required(),
                            ]),
                        Tab::make('Referenzen')
                            ->schema([
                                TextInput::make('reference_header')->required(),
                                TextInput::make('reference_subheader')->required(),
                                RichEditor::make('reference_introtext')->required(),
                            ]),
                        Tab::make('Kontakt')
                            ->schema([
                                TextInput::make('contact_header')->required(),
                                TextInput::make('contact_subheader')->required(),
                                RichEditor::make('contact_introtext')->required(),
                            ]),

                        Tab::make('Kompetenzen')
                            ->schema([
                                TextInput::make('competence_header')->required(),
                                TextInput::make('competence_subheader')->required(),
                                RichEditor::make('competence_introtext')->required(),
                            ]),
                    ])
                    ->persistTabInQueryString(true),

            ])->columns(1);
    }
}
