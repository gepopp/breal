<?php

namespace App\Livewire\Pages;

use App\Models\Realty;
use Livewire\Component;

class Immobilie extends Component
{
    public Realty $realty;



    public function render()
    {
        $data = $this->realty->data;
        return view('livewire.pages.immobilie', compact('data'));
    }
}
