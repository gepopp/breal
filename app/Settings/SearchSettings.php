<?php

namespace App\Settings;

class SearchSettings extends BaseSettings
{
    // Search/Immobiliensuche Page
    public string $search_header_de = 'home';

    public string $search_header_en = 'home';

    public string $search_subheader_de = 'Immobiliensuche der be real makler';

    public string $search_subheader_en = 'Property Search by be real makler';

    public string $search_introtext_de = '<p>Hier finden Sie eine Auswahl der aktuellen, von uns angebotenen Immobilien. Wenn Sie am Verkauf oder der Vermietung Ihrer Liegenschaft interessiert sind, kontaktieren Sie uns gerne!&nbsp;</p>';

    public string $search_introtext_en = '<p>Here you will find a selection of the properties we currently offer. If you are interested in selling or renting your property, please feel free to contact us!&nbsp;</p>';

    public static function group(): string
    {
        return 'translatable_search';
    }
}
