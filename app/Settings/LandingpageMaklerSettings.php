<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class LandingpageMaklerSettings extends Settings
{
    public array|int|null $hero_images = null;


    public string $intro_title = 'realtor';
    public string $intro_subtitle = 'Keine Immobilie gleicht der anderen und jede hat ihre eigene Geschichte.';
    public string $intro_description = '<p>Bei uns dreht sich alles um die Immobilienvermittlung im Bereich Wohnen und Gewerbe, sei es in Miete oder Eigentum.</p><p>Gerne stehen wir Ihnen neben der Immobilienvermittlung einzelner Objekte auch für Vermarktungsfragen oder mit einer auf Sie und Ihr Projekt angepasste Vermarktungsstrategie gerne zur Verfügung.</p>';


    public static function group(): string
    {
        return 'MaklerSettings';
    }
}