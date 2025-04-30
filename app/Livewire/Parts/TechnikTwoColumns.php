<?php

namespace App\Livewire\Parts;

use App\Settings\LandingpageTechnikSettings;
use Livewire\Component;

class TechnikTwoColumns extends Component
{
    public function render( LandingpageTechnikSettings $settings)
    {
        return view('livewire.parts.technik-two-columns', compact('settings'));
    }
}
