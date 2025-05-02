<?php

namespace App\Livewire\Parts;

use App\Settings\LandingpageHausverwaltung;
use Livewire\Component;

class TwoColumnText extends Component
{
    public function render( LandingpageHausverwaltung $settings )
    {
        return view('livewire.parts.two-column-text', compact('settings'));
    }
}
