<?php

namespace App\Livewire\Landing;

use App\Settings\LandingpageTechnikSettings;
use Livewire\Component;

class Technik extends Component
{
    public function render(LandingpageTechnikSettings $settings)
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
