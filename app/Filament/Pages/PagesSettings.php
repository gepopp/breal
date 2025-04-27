<?php

namespace App\Filament\Pages;

use App\Forms\Components\SettingsUpload;
use App\Forms\Components\SpatieMediaUploadToSetting;
use App\Models\Settings;
use App\Settings\Seiteneinstellungen;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Filament\Resources\Components\Tab;
use Illuminate\Http\UploadedFile;
use Spatie\LaravelSettings\Models\SettingsProperty;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class PagesSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Seiteneinstellungen';
    protected static ?string $title = 'Seiteneinstellungen';

    protected static string $settings = \App\Settings\PagesSettings::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Seiten')
                    ->schema([
                        Forms\Components\Tabs\Tab::make('Kontaktseite')
                            ->schema([
                                Forms\Components\TextInput::make('contact_header')->label('Header')->required(),
                                Forms\Components\TextInput::make('contact_subheader')->label('Überschrift')->required(),
                                Forms\Components\RichEditor::make('contact_introtext')->label('Introtext')->required(),

                                Forms\Components\TextInput::make('contactform_heading')->label('Überschrift Kontaktformular')->required(),
                                Forms\Components\TextInput::make('contactform_phone')->label('Telefonnummer')->required(),
                                Forms\Components\TextInput::make('contactform_email')->label('E-Mail-Adresse')->email()->required(),
                                Forms\Components\TextInput::make('contactform_address')->label('Adresse')->required(),

                                Forms\Components\TextInput::make('contactpersons_heading')->label('Kontaktpersonen Überschrift')->required(),
                                Forms\Components\RichEditor::make('contactpersons_introtext')->label('Introtext')->required(),
                            ]),
                        Forms\Components\Tabs\Tab::make('Karriereseite')
                            ->schema([
                                Forms\Components\TextInput::make('vacancies_header')->label('Introtext')->required(),
                                Forms\Components\TextInput::make('vacancies_subheader')->label('Introtext')->required(),
                                Forms\Components\RichEditor::make('vacancies_introtext')->label('Introtext')->required(),
                                Forms\Components\RichEditor::make('cold_application_cta_text')->label('Text für Initiativbewerbungs-CTA"')->required(),
                            ]),


                        Forms\Components\Tabs\Tab::make('Teamseite')
                            ->schema([
                                Forms\Components\TextInput::make('team_header')->label('Introtext')->required(),
                                Forms\Components\TextInput::make('team_subheader')->label('Introtext')->required(),
                                Forms\Components\RichEditor::make('team_introtext')->label('Text für Initiativbewerbungs-CTA"')->required(),
                            ]),


                        Forms\Components\Tabs\Tab::make('Service Seite')
                            ->schema([
                                Forms\Components\TextInput::make('services_header')->label('Introtext')->required(),
                                Forms\Components\TextInput::make('services_subheader')->label('Introtext')->required(),
                                Forms\Components\RichEditor::make('services_introtext')->label('Text für Initiativbewerbungs-CTA"')->required(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Impressum')
                            ->schema([
                                Forms\Components\RichEditor::make('imprint_text')->label('Text für Initiativbewerbungs-CTA"')->required(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Datenschutz')
                            ->schema([
                                Forms\Components\RichEditor::make('dpgr_text')->label('Text für Initiativbewerbungs-CTA"')->required(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Barrierefreiheit')
                            ->schema([
                                Forms\Components\RichEditor::make('accessability_text')->label('Text für Initiativbewerbungs-CTA"')->required(),
                            ]),

                    ])->persistTabInQueryString()

            ])->columns(1);
    }
}
