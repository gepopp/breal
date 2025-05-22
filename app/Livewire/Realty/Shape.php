<?php

namespace App\Livewire\Realty;

use App\Models\Realty;
use Livewire\Component;

class Shape extends Component
{
    public Realty $realty;


    public function render()
    {
        $data = $this->realty->data['zustand_angaben'];
        $ausstattung = $this->realty->data['ausstattung'];
        return view('livewire.realty.shape', compact('data', 'ausstattung'));
    }
}
