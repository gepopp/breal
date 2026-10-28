<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class PagesSettings extends SettingsPage
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-adjustments-horizontal';

    protected static ?string $navigationLabel = 'Seiteneinstellungen';

    protected static ?string $title = 'Seiteneinstellungen';

    protected static string $settings = \App\Settings\PagesSettings::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Seiten')
                    ->schema([
                        Tab::make('Kontaktseite')
                            ->schema([
                                TextInput::make('contact_header')->label('Header')->required(),
                                TextInput::make('contact_subheader')->label('Überschrift')->required(),
                                RichEditor::make('contact_introtext')->label('Introtext')->required(),

                                Section::make('Kontaktdaten')
                                    ->description('Adresse, Telefon, E-Mail-Adressen und Öffnungszeiten werden zentral in config/company.php gepflegt und überall gleich ausgegeben.')
                                    ->schema([]),

                                Section::make('Überschrift Kontaktformular und Kontaktpersonen')
                                    ->description('Diese Texte (Deutsch und Englisch) werden unter "Kontaktseiten" bearbeitet.')
                                    ->schema([]),
                            ]),
                        Tab::make('Karriereseite')
                            ->schema([
                                TextInput::make('vacancies_header')->label('Überschrift')->required(),
                                TextInput::make('vacancies_subheader')->label('Unterzeile')->required(),
                                RichEditor::make('vacancies_introtext')->label('Introtext')->required(),
                                RichEditor::make('cold_application_cta_text')->label('Text für Initiativbewerbungs-CTA"')->required(),
                            ]),

                        Tab::make('Teamseite')
                            ->schema([
                                TextInput::make('team_header')->label('Überschrift')->required(),
                                TextInput::make('team_subheader')->label('Unterzeile')->required(),
                                RichEditor::make('team_introtext')->label('Introtext"')->required(),
                            ]),

                        Tab::make('Service Seite')
                            ->schema([
                                Section::make('Service Seite')
                                    ->description('Überschrift, Unterzeile und Introtext (Deutsch und Englisch) werden unter "Serviceseite" bearbeitet.')
                                    ->schema([]),
                            ]),

                        Tab::make('FAQ Seite')
                            ->schema([
                                TextInput::make('faq_header')->label('Überschrift')->required(),
                                TextInput::make('faq_subheader')->label('Unterzeile')->required(),
                                RichEditor::make('faq_introtext')->label('Introtext"')->required(),
                            ]),

                        Tab::make('Leistungen Seite')
                            ->schema([

                                Section::make('Hausverwaltung')
                                    ->schema([
                                        TextInput::make('hausverwaltung_leistungen_header')->label('Überschrift')->required(),
                                        TextInput::make('hausverwaltung_leistungen_subheader')->label('Unterzeile')->required(),
                                        RichEditor::make('hausverwaltung_leistungen_introtext')->label('Introtext"')->required(),
                                    ]),

                                Section::make('Makler')
                                    ->schema([
                                        TextInput::make('immobilien_leistungen_header')->label('Überschrift')->required(),
                                        TextInput::make('immobilien_leistungen_subheader')->label('Unterzeile')->required(),
                                        RichEditor::make('immobilien_leistungen_introtext')->label('Introtext"')->required(),
                                    ]),
                                Section::make('Technik')
                                    ->schema([
                                        TextInput::make('technik_leistungen_header')->label('Überschrift')->required(),
                                        TextInput::make('technik_leistungen_subheader')->label('Unterzeile')->required(),
                                        RichEditor::make('technik_leistungen_introtext')->label('Introtext"')->required(),
                                    ]),
                            ]),

                        Tab::make('Immobiliensuche')
                            ->schema([
                                TextInput::make('search_header')->label('Überschrift')->required(),
                                TextInput::make('search_subheader')->label('Unterzeile')->required(),
                                RichEditor::make('search_introtext')->label('Introtext"')->required(),
                            ]),

                        Tab::make('Impressum')
                            ->schema([
                                Section::make('Impressum')
                                    ->description('Das Impressum wird aus den Firmendaten in config/company.php erzeugt (Adresse, Geschäftsführung, Firmenbuch, UID, Kontakt je Gesellschaft) und ist hier nicht mehr bearbeitbar.')
                                    ->schema([]),
                            ]),

                        Tab::make('Datenschutz')
                            ->schema([
                                Section::make('Datenschutz')
                                    ->description('Der Datenschutztext (Deutsch und Englisch) wird unter "Rechtliches" bearbeitet.')
                                    ->schema([]),
                            ]),

                        Tab::make('Barrierefreiheit')
                            ->schema([
                                Section::make('Barrierefreiheit')
                                    ->description('Die Barrierefreiheitserklärung (Deutsch und Englisch) wird unter "Rechtliches" bearbeitet.')
                                    ->schema([]),
                            ]),

                    ])->persistTabInQueryString(),

            ])->columns(1);
    }
}
