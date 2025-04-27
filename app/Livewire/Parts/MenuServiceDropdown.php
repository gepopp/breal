<?php

namespace App\Livewire\Parts;

use Livewire\Component;


class MenuServiceDropdown extends Component
{

    public ?array $services = null;


    public function mount()
    {
        $this->services = \App\Models\Service::all()->toArray();
    }

    public function render()
    {

        return view('livewire.parts.menu-service-dropdown');
    }
}
