<?php

namespace App\Livewire\Realty;

use App\Models\Realty;
use Livewire\Component;

class Contact extends Component
{
    public Realty $realty;

    public function render()
    {
        return view('livewire.realty.contact');
    }
}
