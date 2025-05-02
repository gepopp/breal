<?php

namespace App\Livewire\Pages;

use App\Settings\PagesSettings;
use Livewire\Component;

class AccessabilityDeclaration extends Component
{
    public function render( PagesSettings $pagesSettings)
    {
        return view('livewire.pages.accessability-declaration', compact('pagesSettings'));
    }
}
