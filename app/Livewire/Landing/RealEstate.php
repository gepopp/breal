<?php

namespace App\Livewire\Landing;

use App\Models\Realty;
use App\Services\PageMeta;
use Livewire\Component;

class RealEstate extends Component
{
    public function render()
    {
        $realEstates = Realty::all();

        return view('livewire.landing.real-estate', compact('realEstates'))
            ->title(PageMeta::title('start'))
            ->layout('components.layouts.site')
            ->layoutData([
                'canonical' => route('makler.home'),
                'description' => PageMeta::description('start'),
            ]);
    }
}
