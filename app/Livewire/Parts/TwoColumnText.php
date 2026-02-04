<?php

namespace App\Livewire\Parts;

use App\Settings\HausverwaltungLandingpageSettings;
use Livewire\Component;

class TwoColumnText extends Component
{
    public function render( HausverwaltungLandingpageSettings $settings )
    {
        return view('livewire.parts.two-column-text', compact('settings'));
    }
}
