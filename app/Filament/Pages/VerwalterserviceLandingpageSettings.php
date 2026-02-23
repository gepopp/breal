<?php

namespace App\Filament\Pages;

use App\Settings\VerwalterserviceLandingpageSettings as Settings;
use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class VerwalterserviceLandingpageSettings extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::Briefcase;

    protected static string $settings = Settings::class;

    protected static string|UnitEnum|null $navigationGroup = 'Landingpages';

    protected static ?string $navigationLabel = 'Verwalterservice';

    protected static ?string $title = 'Verwalterservice Landingpage Einstellungen';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Hero Bereich')
                ->schema([
                    Grid::make(2)->schema([
                        Textarea::make('hero_tagline_de')
                            ->label('Hero Tagline (Deutsch)')
                            ->required(),
                        Textarea::make('hero_tagline_en')
                            ->label('Hero Tagline (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        Textarea::make('hero_headline_de')
                            ->label('Hero Headline (Deutsch)')
                            ->required(),
                        Textarea::make('hero_headline_en')
                            ->label('Hero Headline (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('hero_email')
                            ->label('Hero E-Mail')
                            ->email()
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('hero_phone')
                            ->label('Hero Telefon')
                            ->tel()
                            ->required()
                            ->columnSpanFull(),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('hero_feature_1_de')
                            ->label('Feature 1 (Deutsch)')
                            ->required(),
                        TextInput::make('hero_feature_1_en')
                            ->label('Feature 1 (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('hero_feature_2_de')
                            ->label('Feature 2 (Deutsch)')
                            ->required(),
                        TextInput::make('hero_feature_2_en')
                            ->label('Feature 2 (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('hero_feature_3_de')
                            ->label('Feature 3 (Deutsch)')
                            ->required(),
                        TextInput::make('hero_feature_3_en')
                            ->label('Feature 3 (English)')
                            ->required(),
                    ]),
                ]),

            Section::make('Sektion 1 - Professionell')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('section_1_tag_de')
                            ->label('Tag (Deutsch)')
                            ->required(),
                        TextInput::make('section_1_tag_en')
                            ->label('Tag (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        Textarea::make('section_1_headline_de')
                            ->label('Überschrift (Deutsch)')
                            ->required(),
                        Textarea::make('section_1_headline_en')
                            ->label('Überschrift (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        Textarea::make('section_1_text_de')
                            ->label('Text (Deutsch)')
                            ->required(),
                        Textarea::make('section_1_text_en')
                            ->label('Text (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('section_1_feature_1_de')
                            ->label('Feature 1 (Deutsch)')
                            ->required(),
                        TextInput::make('section_1_feature_1_en')
                            ->label('Feature 1 (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('section_1_feature_2_de')
                            ->label('Feature 2 (Deutsch)')
                            ->required(),
                        TextInput::make('section_1_feature_2_en')
                            ->label('Feature 2 (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('section_1_feature_3_de')
                            ->label('Feature 3 (Deutsch)')
                            ->required(),
                        TextInput::make('section_1_feature_3_en')
                            ->label('Feature 3 (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('section_1_feature_4_de')
                            ->label('Feature 4 (Deutsch)')
                            ->required(),
                        TextInput::make('section_1_feature_4_en')
                            ->label('Feature 4 (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('section_1_feature_5_de')
                            ->label('Feature 5 (Deutsch)')
                            ->required(),
                        TextInput::make('section_1_feature_5_en')
                            ->label('Feature 5 (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('section_1_feature_6_de')
                            ->label('Feature 6 (Deutsch)')
                            ->required(),
                        TextInput::make('section_1_feature_6_en')
                            ->label('Feature 6 (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('section_1_cta_de')
                            ->label('CTA Button (Deutsch)')
                            ->required(),
                        TextInput::make('section_1_cta_en')
                            ->label('CTA Button (English)')
                            ->required(),
                    ]),
                ]),

            Section::make('Sektion 2 - Services Grid')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('section_2_tag_de')
                            ->label('Tag (Deutsch)')
                            ->required(),
                        TextInput::make('section_2_tag_en')
                            ->label('Tag (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        Textarea::make('section_2_headline_de')
                            ->label('Überschrift (Deutsch)')
                            ->required(),
                        Textarea::make('section_2_headline_en')
                            ->label('Überschrift (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        Textarea::make('section_2_text_de')
                            ->label('Text (Deutsch)')
                            ->required(),
                        Textarea::make('section_2_text_en')
                            ->label('Text (English)')
                            ->required(),
                    ]),

                    Section::make('Service 1')
                        ->schema([
                            Grid::make(2)->schema([
                                TextInput::make('service_1_title_de')
                                    ->label('Titel (Deutsch)')
                                    ->required(),
                                TextInput::make('service_1_title_en')
                                    ->label('Titel (English)')
                                    ->required(),
                            ]),
                            Grid::make(2)->schema([
                                Textarea::make('service_1_description_de')
                                    ->label('Beschreibung (Deutsch)')
                                    ->required(),
                                Textarea::make('service_1_description_en')
                                    ->label('Beschreibung (English)')
                                    ->required(),
                            ]),
                        ])->collapsible(),

                    Section::make('Service 2')
                        ->schema([
                            Grid::make(2)->schema([
                                TextInput::make('service_2_title_de')
                                    ->label('Titel (Deutsch)')
                                    ->required(),
                                TextInput::make('service_2_title_en')
                                    ->label('Titel (English)')
                                    ->required(),
                            ]),
                            Grid::make(2)->schema([
                                Textarea::make('service_2_description_de')
                                    ->label('Beschreibung (Deutsch)')
                                    ->required(),
                                Textarea::make('service_2_description_en')
                                    ->label('Beschreibung (English)')
                                    ->required(),
                            ]),
                        ])->collapsible(),

                    Section::make('Service 3')
                        ->schema([
                            Grid::make(2)->schema([
                                TextInput::make('service_3_title_de')
                                    ->label('Titel (Deutsch)')
                                    ->required(),
                                TextInput::make('service_3_title_en')
                                    ->label('Titel (English)')
                                    ->required(),
                            ]),
                            Grid::make(2)->schema([
                                Textarea::make('service_3_description_de')
                                    ->label('Beschreibung (Deutsch)')
                                    ->required(),
                                Textarea::make('service_3_description_en')
                                    ->label('Beschreibung (English)')
                                    ->required(),
                            ]),
                        ])->collapsible(),

                    Section::make('Service 4')
                        ->schema([
                            Grid::make(2)->schema([
                                TextInput::make('service_4_title_de')
                                    ->label('Titel (Deutsch)')
                                    ->required(),
                                TextInput::make('service_4_title_en')
                                    ->label('Titel (English)')
                                    ->required(),
                            ]),
                            Grid::make(2)->schema([
                                Textarea::make('service_4_description_de')
                                    ->label('Beschreibung (Deutsch)')
                                    ->required(),
                                Textarea::make('service_4_description_en')
                                    ->label('Beschreibung (English)')
                                    ->required(),
                            ]),
                        ])->collapsible(),

                    Section::make('Service 5')
                        ->schema([
                            Grid::make(2)->schema([
                                TextInput::make('service_5_title_de')
                                    ->label('Titel (Deutsch)')
                                    ->required(),
                                TextInput::make('service_5_title_en')
                                    ->label('Titel (English)')
                                    ->required(),
                            ]),
                            Grid::make(2)->schema([
                                Textarea::make('service_5_description_de')
                                    ->label('Beschreibung (Deutsch)')
                                    ->required(),
                                Textarea::make('service_5_description_en')
                                    ->label('Beschreibung (English)')
                                    ->required(),
                            ]),
                        ])->collapsible(),

                    Section::make('Service 6')
                        ->schema([
                            Grid::make(2)->schema([
                                TextInput::make('service_6_title_de')
                                    ->label('Titel (Deutsch)')
                                    ->required(),
                                TextInput::make('service_6_title_en')
                                    ->label('Titel (English)')
                                    ->required(),
                            ]),
                            Grid::make(2)->schema([
                                Textarea::make('service_6_description_de')
                                    ->label('Beschreibung (Deutsch)')
                                    ->required(),
                                Textarea::make('service_6_description_en')
                                    ->label('Beschreibung (English)')
                                    ->required(),
                            ]),
                        ])->collapsible(),

                    Grid::make(2)->schema([
                        TextInput::make('section_2_cta_text_de')
                            ->label('CTA Text (Deutsch)')
                            ->required(),
                        TextInput::make('section_2_cta_text_en')
                            ->label('CTA Text (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('section_2_cta_button_de')
                            ->label('CTA Button (Deutsch)')
                            ->required(),
                        TextInput::make('section_2_cta_button_en')
                            ->label('CTA Button (English)')
                            ->required(),
                    ]),
                ]),

            Section::make('Sektion 3 - Vertrauen')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('section_3_tag_de')
                            ->label('Tag (Deutsch)')
                            ->required(),
                        TextInput::make('section_3_tag_en')
                            ->label('Tag (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        Textarea::make('section_3_headline_de')
                            ->label('Überschrift (Deutsch)')
                            ->required(),
                        Textarea::make('section_3_headline_en')
                            ->label('Überschrift (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        Textarea::make('section_3_text_de')
                            ->label('Text (Deutsch)')
                            ->required(),
                        Textarea::make('section_3_text_en')
                            ->label('Text (English)')
                            ->required(),
                    ]),

                    Section::make('Trust Features')
                        ->schema([
                            Grid::make(2)->schema([
                                TextInput::make('trust_1_title_de')
                                    ->label('Feature 1 Titel (Deutsch)')
                                    ->required(),
                                TextInput::make('trust_1_title_en')
                                    ->label('Feature 1 Titel (English)')
                                    ->required(),
                            ]),
                            Grid::make(2)->schema([
                                Textarea::make('trust_1_text_de')
                                    ->label('Feature 1 Text (Deutsch)')
                                    ->required(),
                                Textarea::make('trust_1_text_en')
                                    ->label('Feature 1 Text (English)')
                                    ->required(),
                            ]),
                            Grid::make(2)->schema([
                                TextInput::make('trust_2_title_de')
                                    ->label('Feature 2 Titel (Deutsch)')
                                    ->required(),
                                TextInput::make('trust_2_title_en')
                                    ->label('Feature 2 Titel (English)')
                                    ->required(),
                            ]),
                            Grid::make(2)->schema([
                                Textarea::make('trust_2_text_de')
                                    ->label('Feature 2 Text (Deutsch)')
                                    ->required(),
                                Textarea::make('trust_2_text_en')
                                    ->label('Feature 2 Text (English)')
                                    ->required(),
                            ]),
                            Grid::make(2)->schema([
                                TextInput::make('trust_3_title_de')
                                    ->label('Feature 3 Titel (Deutsch)')
                                    ->required(),
                                TextInput::make('trust_3_title_en')
                                    ->label('Feature 3 Titel (English)')
                                    ->required(),
                            ]),
                            Grid::make(2)->schema([
                                Textarea::make('trust_3_text_de')
                                    ->label('Feature 3 Text (Deutsch)')
                                    ->required(),
                                Textarea::make('trust_3_text_en')
                                    ->label('Feature 3 Text (English)')
                                    ->required(),
                            ]),
                            Grid::make(2)->schema([
                                TextInput::make('trust_4_title_de')
                                    ->label('Feature 4 Titel (Deutsch)')
                                    ->required(),
                                TextInput::make('trust_4_title_en')
                                    ->label('Feature 4 Titel (English)')
                                    ->required(),
                            ]),
                            Grid::make(2)->schema([
                                Textarea::make('trust_4_text_de')
                                    ->label('Feature 4 Text (Deutsch)')
                                    ->required(),
                                Textarea::make('trust_4_text_en')
                                    ->label('Feature 4 Text (English)')
                                    ->required(),
                            ]),
                            Grid::make(2)->schema([
                                TextInput::make('trust_5_title_de')
                                    ->label('Feature 5 Titel (Deutsch)')
                                    ->required(),
                                TextInput::make('trust_5_title_en')
                                    ->label('Feature 5 Titel (English)')
                                    ->required(),
                            ]),
                            Grid::make(2)->schema([
                                Textarea::make('trust_5_text_de')
                                    ->label('Feature 5 Text (Deutsch)')
                                    ->required(),
                                Textarea::make('trust_5_text_en')
                                    ->label('Feature 5 Text (English)')
                                    ->required(),
                            ]),
                            Grid::make(2)->schema([
                                TextInput::make('trust_6_title_de')
                                    ->label('Feature 6 Titel (Deutsch)')
                                    ->required(),
                                TextInput::make('trust_6_title_en')
                                    ->label('Feature 6 Titel (English)')
                                    ->required(),
                            ]),
                            Grid::make(2)->schema([
                                Textarea::make('trust_6_text_de')
                                    ->label('Feature 6 Text (Deutsch)')
                                    ->required(),
                                Textarea::make('trust_6_text_en')
                                    ->label('Feature 6 Text (English)')
                                    ->required(),
                            ]),
                        ])->collapsible(),
                ]),

            Section::make('Sektion 4 - So funktioniert es')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('section_4_tag_de')
                            ->label('Tag (Deutsch)')
                            ->required(),
                        TextInput::make('section_4_tag_en')
                            ->label('Tag (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        Textarea::make('section_4_headline_de')
                            ->label('Überschrift (Deutsch)')
                            ->required(),
                        Textarea::make('section_4_headline_en')
                            ->label('Überschrift (English)')
                            ->required(),
                    ]),
                    TextInput::make('section_4_stat_number')
                        ->label('Statistik Zahl')
                        ->required()
                        ->columnSpanFull(),
                    Grid::make(2)->schema([
                        TextInput::make('section_4_stat_text_de')
                            ->label('Statistik Text (Deutsch)')
                            ->required(),
                        TextInput::make('section_4_stat_text_en')
                            ->label('Statistik Text (English)')
                            ->required(),
                    ]),

                    Section::make('Steps')
                        ->schema([
                            Grid::make(2)->schema([
                                TextInput::make('step_1_title_de')
                                    ->label('Schritt 1 Titel (Deutsch)')
                                    ->required(),
                                TextInput::make('step_1_title_en')
                                    ->label('Schritt 1 Titel (English)')
                                    ->required(),
                            ]),
                            Grid::make(2)->schema([
                                Textarea::make('step_1_text_de')
                                    ->label('Schritt 1 Text (Deutsch)')
                                    ->required(),
                                Textarea::make('step_1_text_en')
                                    ->label('Schritt 1 Text (English)')
                                    ->required(),
                            ]),
                            Grid::make(2)->schema([
                                TextInput::make('step_2_title_de')
                                    ->label('Schritt 2 Titel (Deutsch)')
                                    ->required(),
                                TextInput::make('step_2_title_en')
                                    ->label('Schritt 2 Titel (English)')
                                    ->required(),
                            ]),
                            Grid::make(2)->schema([
                                Textarea::make('step_2_text_de')
                                    ->label('Schritt 2 Text (Deutsch)')
                                    ->required(),
                                Textarea::make('step_2_text_en')
                                    ->label('Schritt 2 Text (English)')
                                    ->required(),
                            ]),
                            Grid::make(2)->schema([
                                TextInput::make('step_3_title_de')
                                    ->label('Schritt 3 Titel (Deutsch)')
                                    ->required(),
                                TextInput::make('step_3_title_en')
                                    ->label('Schritt 3 Titel (English)')
                                    ->required(),
                            ]),
                            Grid::make(2)->schema([
                                Textarea::make('step_3_text_de')
                                    ->label('Schritt 3 Text (Deutsch)')
                                    ->required(),
                                Textarea::make('step_3_text_en')
                                    ->label('Schritt 3 Text (English)')
                                    ->required(),
                            ]),
                        ])->collapsible(),
                ]),

            Section::make('CTA Bereich')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('cta_tag_de')
                            ->label('Tag (Deutsch)')
                            ->required(),
                        TextInput::make('cta_tag_en')
                            ->label('Tag (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        Textarea::make('cta_headline_de')
                            ->label('Überschrift (Deutsch)')
                            ->required(),
                        Textarea::make('cta_headline_en')
                            ->label('Überschrift (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        Textarea::make('cta_text_de')
                            ->label('Text (Deutsch)')
                            ->required(),
                        Textarea::make('cta_text_en')
                            ->label('Text (English)')
                            ->required(),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make('cta_button_de')
                            ->label('Button Text (Deutsch)')
                            ->required(),
                        TextInput::make('cta_button_en')
                            ->label('Button Text (English)')
                            ->required(),
                    ]),
                ]),
        ])->columns(1);
    }
}
