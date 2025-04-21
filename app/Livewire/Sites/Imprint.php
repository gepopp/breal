<?php

namespace App\Livewire\Sites;

use App\Settings\PagesSettings;
use Livewire\Attributes\Layout;
use Livewire\Component;


#[Layout('components.site')]
class Imprint extends Component
{
    public function render( PagesSettings $pagesSettings )
    {
        return view('livewire.sites.imprint', compact('pagesSettings'));
    }
}
