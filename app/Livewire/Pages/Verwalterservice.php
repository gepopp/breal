<?php

namespace App\Livewire\Pages;

use App\Settings\VerwalterserviceLandingpageSettings;
use Livewire\Component;

class Verwalterservice extends Component
{
    public function render()
    {
        return view('livewire.pages.verwalterservice', [
            'settings' => app(VerwalterserviceLandingpageSettings::class),
        ]);
    }
}
