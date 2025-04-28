<?php

namespace App\Livewire\Parts;

use App\Enums\CompaniesEnum;
use Livewire\Component;


class MenuServiceDropdown extends Component
{

    public string $company = CompaniesEnum::Hausverwaltung->name;

    public ?array $services = null;


    public function mount()
    {
        $this->company = CompaniesEnum::getByRoute();

        $this->services = \App\Models\Service::all()->toArray();
    }

    public function render()
    {

        return view('livewire.parts.menu-service-dropdown');
    }
}
