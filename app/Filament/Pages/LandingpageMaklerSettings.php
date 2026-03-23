<?php

namespace App\Filament\Pages;

use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\RichEditor;
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

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected static string $settings = \App\Settings\LandingpageMaklerSettings::class;

    protected static ?string $navigationLabel = 'Landingpage Makler';

    protected static ?string $title = 'Landingpage Makler';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                Tabs::make('Sektionen')
                    ->schema([
                        Tab::make('Intro')
                            ->schema([
                                SettingsUpload::make('hero_images')
                                    ->disk('s3')
                                    ->multiple()
                                    ->reorderable()
                                    ->panelLayout('grid')
                                    ->required(),

                                TextInput::make('intro_title'),
                                TextInput::make('intro_subtitle'),
                                RichEditor::make('intro_description'),
                            ]),

                        Tab::make('Über uns')
                            ->schema([
                                SettingsUpload::make('about_image')
                                    ->disk('s3')
                                    ->required(),
                                TextInput::make('about_image_alt')->required(),
                                TextInput::make('about_heading')->required(),
                                RichEditor::make('about_text')->required(),
                            ]),

                        Tab::make('CTA')
                            ->schema([
                                TextInput::make('cta_header')->required(),
                                TextInput::make('cta_subheader')->required(),
                                RichEditor::make('cta_text')->required(),
                                SettingsUpload::make('cta_image')->disk('s3')->required(),
                                TextInput::make('cta_image_alt')->required(),
                                Textarea::make('cta_video_embed_code'),
                            ]),

                    ]),
            ])->columns(1);
    }
}
