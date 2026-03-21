<?php

namespace App\Livewire\Pages;

use App\Models\ServiceCard;
use App\Settings\VerwalterserviceLandingpageSettings;
use Livewire\Component;

class Verwalterservice extends Component
{
    public function render()
    {
        return view('livewire.pages.verwalterservice', [
            'settings' => app(VerwalterserviceLandingpageSettings::class),
            'serviceCards' => ServiceCard::where('type', 'service')->orderBy('order')->get(),
            'featureCards' => ServiceCard::where('type', 'feature')->orderBy('order')->get(),
        ]);
    }
}
