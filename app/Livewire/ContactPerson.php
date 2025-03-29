<?php

namespace App\Livewire;

use Livewire\Component;

class ContactPerson extends Component
{
    public \App\Models\Contactperson $contactperson;


    public function render()
    {
        return view('livewire.contact-person');
    }
}
