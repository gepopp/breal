<?php

namespace App\Livewire\Hausverwaltung;

use App\Enums\CompaniesEnum;
use Livewire\Component;

class Kontakt extends Component
{
    public string $company = CompaniesEnum::Hausverwaltung->name;


    public function mount()
    {
        $this->company = CompaniesEnum::getByRoute();
    }

    public function render()
    {
        return view('livewire.hausverwaltung.kontakt');
    }
}
