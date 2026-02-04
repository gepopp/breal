<?php

namespace App\Livewire\Landing;

use App\Settings\TechnikLandingpageSettings;
use Livewire\Component;

class Technik extends Component
{
    public function render(TechnikLandingpageSettings $settings)
    {
        return view('livewire.landing.technik', compact('settings'))
            ->title(__('landing.technik.title'))
            ->layout('components.layouts.site')
            ->layoutData([
                'canonical' => route('technik.home'),
                'description' => __('landing.technik.description'),
            ]);
    }
}
