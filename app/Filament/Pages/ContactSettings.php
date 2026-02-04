<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use UnitEnum;

class ContactSettings extends SettingsPage
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-phone';

    protected static ?string $navigationLabel = 'Kontaktseiten';

    protected static UnitEnum|string|null $navigationGroup = 'Seiteneinstellungen';

    protected static ?string $title = 'Kontaktseiten Einstellungen';

    protected static string $settings = \App\Settings\ContactSettings::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Kontaktseite Header')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('contact_header_de')
                                    ->label('Header (Deutsch)')
                                    ->required(),
                                Forms\Components\TextInput::make('contact_header_en')
                                    ->label('Header (English)')
                                    ->required(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('contact_subheader_de')
                                    ->label('Überschrift (Deutsch)')
                                    ->required(),
                                Forms\Components\TextInput::make('contact_subheader_en')
                                    ->label('Subheader (English)')
                                    ->required(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Forms\Components\RichEditor::make('contact_introtext_de')
                                    ->label('Introtext (Deutsch)'),
                                Forms\Components\RichEditor::make('contact_introtext_en')
                                    ->label('Introtext (English)'),
                            ]),
                    ]),

                Section::make('Kontaktformular')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('contactform_heading_de')
                                    ->label('Überschrift (Deutsch)')
                                    ->required(),
                                Forms\Components\TextInput::make('contactform_heading_en')
                                    ->label('Heading (English)')
                                    ->required(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Textarea::make('contactform_terms_de')
                                    ->label('Nutzungsbedingungen (Deutsch)')
                                    ->rows(3)
                                    ->required(),
                                Forms\Components\Textarea::make('contactform_terms_en')
                                    ->label('Terms (English)')
                                    ->rows(3)
                                    ->required(),
                            ]),
                    ]),

                Section::make('Kontaktdaten Hausverwaltung')
                    ->schema([
                        Forms\Components\TextInput::make('contactform_email')
                            ->label('E-Mail-Adresse')
                            ->email()
                            ->required(),
                        Forms\Components\TextInput::make('contactform_phone')
                            ->label('Telefonnummer')
                            ->required(),
                        Forms\Components\TextInput::make('contactform_address')
                            ->label('Adresse')
                            ->required(),
                    ]),

                Section::make('Kontaktdaten Makler')
                    ->schema([
                        Forms\Components\TextInput::make('makler_contactform_email')
                            ->label('E-Mail-Adresse')
                            ->email()
                            ->required(),
                        Forms\Components\TextInput::make('makler_contactform_phone')
                            ->label('Telefonnummer')
                            ->required(),
                        Forms\Components\TextInput::make('makler_contactform_address')
                            ->label('Adresse')
                            ->required(),
                    ]),

                Section::make('Kontaktdaten Technik')
                    ->schema([
                        Forms\Components\TextInput::make('technik_contactform_email')
                            ->label('E-Mail-Adresse')
                            ->email()
                            ->required(),
                        Forms\Components\TextInput::make('technik_contactform_phone')
                            ->label('Telefonnummer')
                            ->required(),
                        Forms\Components\TextInput::make('technik_contactform_address')
                            ->label('Adresse')
                            ->required(),
                    ]),

                Section::make('Kontaktpersonen')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('contactpersons_heading_de')
                                    ->label('Überschrift (Deutsch)')
                                    ->required(),
                                Forms\Components\TextInput::make('contactpersons_heading_en')
                                    ->label('Heading (English)')
                                    ->required(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Forms\Components\RichEditor::make('contactpersons_introtext_de')
                                    ->label('Introtext (Deutsch)'),
                                Forms\Components\RichEditor::make('contactpersons_introtext_en')
                                    ->label('Introtext (English)'),
                            ]),
                    ]),
            ])
            ->columns(1);
    }
}
