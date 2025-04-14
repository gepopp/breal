<?php

namespace App\Livewire\Subparts;

use Livewire\Attributes\Lazy;
use Livewire\Component;

class Timeline extends Component
{
    public function render()
    {
        $timeline = \App\Models\Timeline::all();

        return view('livewire.subparts.timeline', compact('timeline'));
    }
}
