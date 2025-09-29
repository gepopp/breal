<?php

namespace App\Livewire\Pages;

use App\Enums\CompaniesEnum;
use App\Settings\PagesSettings;
use App\Traits\SplitsHtmlText;
use Livewire\Component;

class Contact extends Component
{
    use SplitsHtmlText;

    public string $company = CompaniesEnum::Hausverwaltung->name;

    public string $text = '';


    public function mount(PagesSettings $pagesSettings)
    {
        $this->company = CompaniesEnum::getByRoute();

        $this->text = $pagesSettings->contact_introtext;
    }

    public function render( PagesSettings $pagesSettings )
    {
        $description ='Vereinbaren Sie ein Beratungsgespräch oder fordern Sie Ihr individuelles Angebot an. be real Immobilienmanagement – persönlich, verlässlich & serviceorientiert.';
        $preparedText = $this->prepareText();
        return view('livewire.pages.contact', compact('pagesSettings', 'preparedText', 'description'))->title('Kontakt | be real Immobilien – Wir sind für Sie da in Wien');
    }
}
