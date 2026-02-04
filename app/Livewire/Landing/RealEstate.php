<?php

namespace App\Livewire\Landing;

use App\Models\Realty;
use Livewire\Component;

class RealEstate extends Component
{
    public function render()
    {
        $realEstates = Realty::all();

        return view('livewire.landing.real-estate', compact('realEstates'))
            ->title(__('landing.real_estate.title'))
            ->layout('components.layouts.site')
            ->layoutData([
                'canonical' => route('makler.home'),
                'description' => __('landing.real_estate.description'),
            ]);
    }
}
