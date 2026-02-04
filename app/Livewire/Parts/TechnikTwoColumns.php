<?php

namespace App\Livewire\Parts;

use App\Settings\TechnikLandingpageSettings;
use Livewire\Component;

class TechnikTwoColumns extends Component
{
    public function render( TechnikLandingpageSettings $settings)
    {
        return view('livewire.parts.technik-two-columns', compact('settings'));
    }
}
