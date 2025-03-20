<?php

namespace App\Livewire\Sites;

use Livewire\Attributes\Layout;
use Livewire\Component;


#[Layout('components.site')]
class Imprint extends Component
{
    public function render()
    {
        return view('livewire.sites.imprint');
    }
}
