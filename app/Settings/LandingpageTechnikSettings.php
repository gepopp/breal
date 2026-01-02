<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class LandingpageTechnikSettings extends Settings
{

    public array|int|null $hero_image = null;

    public string $hero_image_alt = 'Bontus Eybel Intro Bild';

    public string $hero_header = 'engineering';

    public string $hero_subheader = 'Die Technik für Hausverwaltung und Immobilienmanagement';

    public string $hero_introtext = '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur delectus ea id maiores quasi reiciendis sed voluptate. Cumque deserunt dignissimos, dolore, excepturi fugiat id libero nostrum qui, quod repellat vero.</p>';


    public string $intro_layout = 'two_columns';

    public string $hero_text_column_one = '<p>Unser Ziel ist es, den Wert jeder Immobilie zu bewahren, aber auch zu steigern, indem wir sie mit Sorgfalt und Hingabe pflegen. Wir streben jeden Tag danach, die Erwartungen unserer Kunden zu übertreffen. Wenn unsere Arbeit so nahtlos verläuft, dass niemand merkt, dass wir im Hintergrund agieren, dann wissen wir, dass wir unsere Aufgabe erfolgreich erfüllt haben. Wir setzen uns mit Begeisterung dafür ein, dass die uns anvertraute Immobilie nicht nur rundum betreut, sondern auch verwaltet und schlussendlich zeitgemäß gemanaged wird.</p>';

    public string $hero_text_column_two = '<p>Sollte aber doch mal wo der Haken drin sein, dann sind wir zur Stelle und bereit jedes Problem zu lösen: von der störrischen Glühbirne über jegliche Schadensfälle bis hin zu komplexen Sanierungen - wir nehmen uns Ihrer Problemen an!</p><p>Es erfüllt uns mit Freude, Herausforderungen zu meistern und maßgeschneiderte Lösungen für Sie zu finden!</p>';

    public string $text = '<p>Und nur dann ist auch die entsprechende Erhaltung des Wertes von Gebäuden sichergestellt. Immobilien wollen gehegt und gepflegt werden, und die Nutzerinnen und Nutzer jeden Tag aufs Neue zufriedengestellt werden. Am liebsten ist es uns eigentlich, wenn man nie mit uns zu tun hat, dann haben wir Vieles richtig gemacht.</p><p>Wenn doch irgendwo der Haken drin ist, dann lieben wir es, Problemlöser zu sein - egal ob es um eine Glühlampe oder eine umfangreiche Sanierung geht.</p><p>Es erfüllt uns mit Freude, Herausforderungen zu meistern und maßgeschneiderte Lösungen für Sie zu finden!</p>';

    public array|int|null $text_image = 0;

    public string $text_image_alt = 'Bontus Eybel Intro Bild';


    public string $about_header = 'with us';

    public string $about_subheader = 'Warum be real?';

    public string $about_text = '<p>Als Familienunternehmen wissen wir, dass viele Menschen unter einem Dach auch viele Meinungen bedeuten können. Nicht nur das, auch die individuellen Bedürfnisse und Wünsche an eine Immobilie können höchst unterschiedlich sein.</p><p>Unser Ansatz: wir verknüpfen Tradition und Moderne sowie unsere Menschenkenntnis, um die passenden Lösungen zu finden. Denn für uns ist Hausverwaltung eine Herzensangelegenheit, die wir mit Engagement und Begeisterung in jedem Projekt leben.</p>';

    public array|int|null $about_image = 0;

    public string $about_image_alt = 'Bontus Eybel Intro Bild';

    public ?string $about_video_embed_code = '';


    public static function group(): string
    {
        return 'LandingpageTechnik';
    }

    public function toArray(): array
    {
        return [
            'hero_image_alt' => $this->hero_image_alt,
            'hero_header' => $this->hero_header,
            'hero_subheader' => $this->hero_subheader,
            'hero_introtext' => $this->hero_introtext,
            'hero_text_column_one' => $this->hero_text_column_one,
            'hero_text_column_two' => $this->hero_text_column_two,
            'text' => $this->text,
            'text_image_alt' => $this->text_image_alt,
            'about_header' => $this->about_header,
            'about_subheader' => $this->about_subheader,
            'about_text' => $this->about_text,
            'about_image_alt' => $this->about_image_alt,
            'about_video_embed_code' => $this->about_video_embed_code,
        ];
    }
}