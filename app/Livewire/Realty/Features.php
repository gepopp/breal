<?php

namespace App\Livewire\Realty;

use App\Models\Realty;
use Livewire\Component;

class Features extends Component
{
    public Realty $realty;


    public function render()
    {
        $ausstattung = $this->realty->data['ausstattung'];
        return view('livewire.realty.features', compact('ausstattung'));
    }
}
