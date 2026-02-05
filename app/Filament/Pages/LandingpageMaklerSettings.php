<?php

namespace App\Filament\Pages;

use App\Forms\Components\SettingsUpload;
use BackedEnum;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class LandingpageMaklerSettings extends SettingsPage
{
    protected static bool $shouldRegisterNavigation = false;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-document-text';

    protected static string $settings = \App\Settings\LandingpageMaklerSettings::class;

    protected static ?string $navigationLabel = 'Landingpage Makler';

    protected static ?string $title = 'Landingpage Makler';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([

                Tabs::make('Sektionen')
                    ->schema([
                        Tabs\Tab::make('Intro')
                            ->schema([
                                SettingsUpload::make('hero_images')
                                    ->multiple()
                                    ->reorderable()
                                    ->panelLayout('grid')
                                    ->required(),

                                TextInput::make('intro_title'),
                                TextInput::make('intro_subtitle'),
                                Forms\Components\RichEditor::make('intro_description'),
                            ]),

                        Tabs\Tab::make('Über uns')
                            ->schema([
                                SettingsUpload::make('about_image')->required(),
                                Forms\Components\TextInput::make('about_image_alt')->required(),
                                Forms\Components\TextInput::make('about_heading')->required(),
                                Forms\Components\RichEditor::make('about_text')->required(),
                            ]),

                        Tabs\Tab::make('CTA')
                            ->schema([
                                Forms\Components\TextInput::make('cta_header')->required(),
                                Forms\Components\TextInput::make('cta_subheader')->required(),
                                Forms\Components\RichEditor::make('cta_text')->required(),
                                SettingsUpload::make('cta_image')->required(),
                                Forms\Components\TextInput::make('cta_image_alt')->required(),
                                Textarea::make('cta_video_embed_code'),
                            ]),

                    ]),
            ])->columns(1);
    }
}
