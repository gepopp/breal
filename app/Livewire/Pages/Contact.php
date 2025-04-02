<?php

namespace App\Livewire\Pages;

use App\Enums\CompaniesEnum;
use App\Settings\PagesSettings;
use Livewire\Component;

class Contact extends Component
{
    public string $company = CompaniesEnum::Hausverwaltung->name;


    public function mount()
    {
        $this->company = CompaniesEnum::getByRoute();
    }

    public function render( PagesSettings $pagesSettings )
    {
        return view('livewire.pages.contact', compact('pagesSettings'));
    }
}
