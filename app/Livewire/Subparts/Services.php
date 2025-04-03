<?php

namespace App\Livewire\Subparts;

use App\Models\Service;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class Services extends Component
{
    public function render()
    {
        $services = Service::where('on_landing', true )->get();

        return view('livewire.subparts.services', compact('services'));
    }
}
