<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class LandingpageMaklerSettings extends Settings
{
    public array|int|null $hero_images = null;

    public string $intro_title = 'realtor';

    public string $intro_subtitle = 'Keine Immobilie gleicht der anderen und jede hat ihre eigene Geschichte.';

    public string $intro_description = '<p>Bei uns dreht sich alles um die Immobilienvermittlung im Bereich Wohnen und Gewerbe, sei es in Miete oder Eigentum.</p><p>Gerne stehen wir Ihnen neben der Immobilienvermittlung einzelner Objekte auch für Vermarktungsfragen oder mit einer auf Sie und Ihr Projekt angepasste Vermarktungsstrategie gerne zur Verfügung.</p>';

    public string $cta_header = 'with us';

    public string $cta_subheader = 'Wir vermitteln auch Ihre Immobilien';

    public string $cta_text = '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam aperiam beatae corporis debitis deserunt dignissimos, dolor dolorum eaque fugit harum incidunt necessitatibus nemo odit optio praesentium recusandae saepe unde vel!</p>';

    public array|int|null $cta_image = 0;

    public string $cta_image_alt = 'Bontus Eybel Intro Bild';

    public ?string $cta_video_embed_code = '';

    public string $about_heading = 'Geschäftsführerin und Eigentümerin Tanja Bachschwöller, BA';

    public string $about_text = '<p>Im Frühjahr 2021 gründete Tanja Bachschwöller mit ihrem Lebensgefährten Lukas Eybel die Immobilienvermittlung BE Real Immobilien. Jahrelange Berufserfahrung, Aneignung von fundiertem Wissen, sowie das facheinschlägige Studium „Real Estate Management“ an einer renommierten Universität in Österreich spiegeln sich in der maßgeschneiderten Beratung und dem charmanten Umgang mit Kunden wider. Aufgrund der hervorragenden Vernetzung in der Branche sowie der Zusammenarbeit mit mehreren Kooperationspartnern bietet sie jedem Immobiliensuchenden eine einwandfreie Abwicklung bei der Anmietung oder beim Kauf einer Immobilie.</p>';

    public array|int|null $about_image = 0;

    public string $about_image_alt = 'Geschäftsführerin und Eigentümerin Tanja Bachschwöller, BA';

    public static function group(): string
    {
        return 'MaklerSettings';
    }

    public function toArray(): array
    {
        return [
            'intro_title' => $this->intro_title,
            'intro_subtitle' => $this->intro_subtitle,
            'intro_description' => $this->intro_description,
            'cta_header' => $this->cta_header,
            'cta_subheader' => $this->cta_subheader,
            'cta_text' => $this->cta_text,
            'cta_image_alt' => $this->cta_image_alt,
            'cta_video_embed_code' => $this->cta_video_embed_code,
            'about_heading' => $this->about_heading,
            'about_text' => $this->about_text,
            'about_image_alt' => $this->about_image_alt,
        ];
    }
}
