<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ServicesSettings extends Settings
{
    // Services Page
    public string $services_header_de = 'secure';

    public string $services_header_en = 'secure';

    public string $services_subheader_de = 'Unser Versprechen';

    public string $services_subheader_en = 'Our Promise';

    public string $services_introtext_de = '<p>Oft muss es schnell und einfach gehen - hier finden Sie alle wichtigen Unterlagen sowie nützliche Tipps für einen reibungslosen und effizienten Betrieb Ihres Gebäudes.</p>';

    public string $services_introtext_en = '<p>Often things need to be quick and easy - here you will find all important documents as well as useful tips for smooth and efficient operation of your building.</p>';

    public static function group(): string
    {
        return 'translatable_services';
    }
}
