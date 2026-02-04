<?php

namespace App\Livewire\Parts;

use App\Settings\HausverwaltungLandingpageSettings;
use Livewire\Component;

class Contact extends Component
{

    public function render( HausverwaltungLandingpageSettings $settings )
    {
        return view('livewire.parts.contact', compact( 'settings') );
    }
}
