<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class PagesSettings extends SettingsPage
{
    protected static bool $shouldRegisterNavigation = false;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-adjustments-horizontal';

    protected static ?string $navigationLabel = 'Seiteneinstellungen';

    protected static ?string $title = 'Seiteneinstellungen';

    protected static string $settings = \App\Settings\PagesSettings::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('Seiten')
                    ->schema([
                        Tabs\Tab::make('Kontaktseite')
                            ->schema([
                                Forms\Components\TextInput::make('contact_header')->label('Header')->required(),
                                Forms\Components\TextInput::make('contact_subheader')->label('Überschrift')->required(),
                                Forms\Components\RichEditor::make('contact_introtext')->label('Introtext')->required(),

                                Forms\Components\TextInput::make('contactform_heading')->label('Überschrift Kontaktformular')->required(),

                                Section::make('Kontaktdaten Hausverwaltung')
                                    ->schema([
                                        Forms\Components\TextInput::make('contactform_phone')->label('Telefonnummer')->required(),
                                        Forms\Components\TextInput::make('contactform_email')->label('E-Mail-Adresse')->email()->required(),
                                        Forms\Components\TextInput::make('contactform_address')->label('Adresse')->required(),

                                    ]),

                                Section::make('Kontaktdaten Makler')
                                    ->schema([
                                        Forms\Components\TextInput::make('makler_contactform_phone')->label('Telefonnummer')->required(),
                                        Forms\Components\TextInput::make('makler_contactform_email')->label('E-Mail-Adresse')->email()->required(),
                                        Forms\Components\TextInput::make('makler_contactform_address')->label('Adresse')->required(),

                                    ]),

                                Section::make('Kontaktdaten Technik')
                                    ->schema([
                                        Forms\Components\TextInput::make('technik_contactform_phone')->label('Telefonnummer')->required(),
                                        Forms\Components\TextInput::make('technik_contactform_email')->label('E-Mail-Adresse')->email()->required(),
                                        Forms\Components\TextInput::make('technik_contactform_address')->label('Adresse')->required(),

                                    ]),

                                Forms\Components\TextInput::make('contactpersons_heading')->label('Kontaktpersonen Überschrift')->required(),
                                Forms\Components\RichEditor::make('contactpersons_introtext')->label('Introtext')->required(),
                            ]),
                        Tabs\Tab::make('Karriereseite')
                            ->schema([
                                Forms\Components\TextInput::make('vacancies_header')->label('Überschrift')->required(),
                                Forms\Components\TextInput::make('vacancies_subheader')->label('Unterzeile')->required(),
                                Forms\Components\RichEditor::make('vacancies_introtext')->label('Introtext')->required(),
                                Forms\Components\RichEditor::make('cold_application_cta_text')->label('Text für Initiativbewerbungs-CTA"')->required(),
                            ]),

                        Tabs\Tab::make('Teamseite')
                            ->schema([
                                Forms\Components\TextInput::make('team_header')->label('Überschrift')->required(),
                                Forms\Components\TextInput::make('team_subheader')->label('Unterzeile')->required(),
                                Forms\Components\RichEditor::make('team_introtext')->label('Introtext"')->required(),
                            ]),

                        Tabs\Tab::make('Service Seite')
                            ->schema([
                                Forms\Components\TextInput::make('services_header')->label('Überschrift')->required(),
                                Forms\Components\TextInput::make('services_subheader')->label('Unterzeile')->required(),
                                Forms\Components\RichEditor::make('services_introtext')->label('Introtext"')->required(),
                            ]),

                        Tabs\Tab::make('FAQ Seite')
                            ->schema([
                                Forms\Components\TextInput::make('faq_header')->label('Überschrift')->required(),
                                Forms\Components\TextInput::make('faq_subheader')->label('Unterzeile')->required(),
                                Forms\Components\RichEditor::make('faq_introtext')->label('Introtext"')->required(),
                            ]),

                        Tabs\Tab::make('Leistungen Seite')
                            ->schema([

                                Section::make('Hausverwaltung')
                                    ->schema([
                                        Forms\Components\TextInput::make('hausverwaltung_leistungen_header')->label('Überschrift')->required(),
                                        Forms\Components\TextInput::make('hausverwaltung_leistungen_subheader')->label('Unterzeile')->required(),
                                        Forms\Components\RichEditor::make('hausverwaltung_leistungen_introtext')->label('Introtext"')->required(),
                                    ]),

                                Section::make('Makler')
                                    ->schema([
                                        Forms\Components\TextInput::make('immobilien_leistungen_header')->label('Überschrift')->required(),
                                        Forms\Components\TextInput::make('immobilien_leistungen_subheader')->label('Unterzeile')->required(),
                                        Forms\Components\RichEditor::make('immobilien_leistungen_introtext')->label('Introtext"')->required(),
                                    ]),
                                Section::make('Technik')
                                    ->schema([
                                        Forms\Components\TextInput::make('technik_leistungen_header')->label('Überschrift')->required(),
                                        Forms\Components\TextInput::make('technik_leistungen_subheader')->label('Unterzeile')->required(),
                                        Forms\Components\RichEditor::make('technik_leistungen_introtext')->label('Introtext"')->required(),
                                    ]),
                            ]),

                        Tabs\Tab::make('Immobiliensuche')
                            ->schema([
                                Forms\Components\TextInput::make('search_header')->label('Überschrift')->required(),
                                Forms\Components\TextInput::make('search_subheader')->label('Unterzeile')->required(),
                                Forms\Components\RichEditor::make('search_introtext')->label('Introtext"')->required(),
                            ]),

                        Tabs\Tab::make('Impressum')
                            ->schema([
                                Forms\Components\RichEditor::make('imprint_text')->label('Text für Initiativbewerbungs-CTA"')->required(),
                            ]),

                        Tabs\Tab::make('Datenschutz')
                            ->schema([
                                Forms\Components\RichEditor::make('dpgr_text')->label('Text für Initiativbewerbungs-CTA"')->required(),
                            ]),

                        Tabs\Tab::make('Barrierefreiheit')
                            ->schema([
                                Forms\Components\RichEditor::make('accessability_text')->label('Text für Initiativbewerbungs-CTA"')->required(),
                            ]),

                    ])->persistTabInQueryString(),

            ])->columns(1);
    }
}
