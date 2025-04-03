<?php

namespace App\Livewire\Parts;

use Livewire\Component;

class Service extends Component
{
    public string $company;

    public string $header;
    public string $subheader;

    public string $text;

    public function render()
    {
        return view('livewire.parts.service');
    }
}
