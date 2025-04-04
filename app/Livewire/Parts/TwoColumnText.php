<?php

namespace App\Livewire\Parts;

use Livewire\Component;

class TwoColumnText extends Component
{
    public string $company;

    public string $columnLeft;

    public string $columnRight;


    public function render()
    {
        return view('livewire.parts.two-column-text');
    }
}
