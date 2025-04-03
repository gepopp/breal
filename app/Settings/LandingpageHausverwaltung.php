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

    public string $text = '<p>Und nur dann ist auch die entsprechende Erhaltung des Wertes von Gebäuden sichergestellt. Immobilien wollen gehegt und gepflegt werden, und die Nutzerinnen und Nutzer jeden Tag aufs Neue zufriedengestellt werden. Am liebsten ist es uns eigentlich, wenn man nie mit uns zu tun hat, dann haben wir Vieles richtig gemacht.</p><p>Wenn doch irgendwo der Haken drin ist, dann lieben wir es, Problemlöser zu sein - egal ob es um eine Glühlampe oder eine umfangreiche Sanierung geht.</p><p>Es erfüllt uns mit Freude, Herausforderungen zu meistern und maßgeschneiderte Lösungen für Sie zu finden!</p>';

    public int $text_image = 0;
    public string $text_image_alt = 'Bontus Eybel Intro Bild';


    public string $about_header = 'with us';
    public string $about_subheader = 'Warum be real?';
    public string $about_text = '<p>Als Familienunternehmen wissen wir, dass viele Menschen unter einem Dach auch viele Meinungen bedeuten können. Nicht nur das, auch die individuellen Bedürfnisse und Wünsche an eine Immobilie können höchst unterschiedlich sein.</p><p>Unser Ansatz: wir verknüpfen Tradition und Moderne sowie unsere Menschenkenntnis, um die passenden Lösungen zu finden. Denn für uns ist Hausverwaltung eine Herzensangelegenheit, die wir mit Engagement und Begeisterung in jedem Projekt leben.</p>';
    public int $about_image = 0;
    public string $about_image_alt = 'Bontus Eybel Intro Bild';
    public ?string $about_video_embed_code = '';


    public string $timeline_header = 'now';
    public string $timeline_subheader = 'Die Geschichte von be real';
    public string $timeline_intro = "<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>";

    public static function group(): string
    {
        return 'hausverwaltung';
    }
}