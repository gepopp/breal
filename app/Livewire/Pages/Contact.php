<?php

namespace App\Livewire\Pages;

use App\Enums\CompaniesEnum;
use Livewire\Component;

class Contact extends Component
{
    public string $company = CompaniesEnum::Hausverwaltung->name;


    public function mount()
    {
        $this->company = CompaniesEnum::getByRoute();
    }

    public function render()
    {
        return view('livewire.pages.contact');
    }
}
