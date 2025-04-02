<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class LandingpageHausverwaltung extends Settings
{
    public int $hero_image = 0;

    public string $hero_image_alt = 'Bontus Eybel Intro Bild';

    public string $hero_header = 'welcome';

    public string $hero_subheader = 'Hausverwaltung und Immobilienmanagement';

    public string $hero_introtext = '<p>Für uns ist Hausverwaltung spannend, aufregend, erfüllend - kurz gesagt: wir brennen dafür. Das professionelle und auch leidenschaftliche Managen von Immobilien sorgt nämlich dafür, dass Menschen sich in ihren Wohnungen wohlfühlen, Unternehmen erfolgreich arbeiten oder Kunstliebhaber durch Galerien streifen können.</p>';



    public static function group(): string
    {
        return 'hausverwaltung';
    }
}