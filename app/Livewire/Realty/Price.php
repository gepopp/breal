<?php

namespace App\Livewire\Realty;

use App\Models\Realty;
use Livewire\Component;

class Price extends Component
{
    public Realty $realty;


    public function render()
    {
        $prices = $this->realty->data['preise'];
        return view('livewire.realty.price', compact('prices'));
    }
}
