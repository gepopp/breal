<?php

namespace App\Filament\Pages;

use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use BackedEnum;
use Filament\Forms;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class PagesSettings extends SettingsPage
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-adjustments-horizontal';

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

                                TextInput::make('contactform_heading')->label('Überschrift Kontaktformular')->required(),

                                Section::make('Kontaktdaten Hausverwaltung')
                                    ->schema([
                                        TextInput::make('contactform_phone')->label('Telefonnummer')->required(),
                                        TextInput::make('contactform_email')->label('E-Mail-Adresse')->email()->required(),
                                        TextInput::make('contactform_address')->label('Adresse')->required(),

                                    ]),

                                Section::make('Kontaktdaten Makler')
                                    ->schema([
                                        TextInput::make('makler_contactform_phone')->label('Telefonnummer')->required(),
                                        TextInput::make('makler_contactform_email')->label('E-Mail-Adresse')->email()->required(),
                                        TextInput::make('makler_contactform_address')->label('Adresse')->required(),

                                    ]),

                                Section::make('Kontaktdaten Technik')
                                    ->schema([
                                        TextInput::make('technik_contactform_phone')->label('Telefonnummer')->required(),
                                        TextInput::make('technik_contactform_email')->label('E-Mail-Adresse')->email()->required(),
                                        TextInput::make('technik_contactform_address')->label('Adresse')->required(),

                                    ]),

                                TextInput::make('contactpersons_heading')->label('Kontaktpersonen Überschrift')->required(),
                                RichEditor::make('contactpersons_introtext')->label('Introtext')->required(),
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
                                TextInput::make('services_header')->label('Überschrift')->required(),
                                TextInput::make('services_subheader')->label('Unterzeile')->required(),
                                RichEditor::make('services_introtext')->label('Introtext"')->required(),
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
                                RichEditor::make('imprint_text')->label('Text für Initiativbewerbungs-CTA"')->required(),
                            ]),

                        Tab::make('Datenschutz')
                            ->schema([
                                RichEditor::make('dpgr_text')->label('Text für Initiativbewerbungs-CTA"')->required(),
                            ]),

                        Tab::make('Barrierefreiheit')
                            ->schema([
                                RichEditor::make('accessability_text')->label('Text für Initiativbewerbungs-CTA"')->required(),
                            ]),

                    ])->persistTabInQueryString(),

            ])->columns(1);
    }
}
