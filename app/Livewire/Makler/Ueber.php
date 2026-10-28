<?php

namespace App\Livewire\Makler;

use App\Services\PageMeta;
use Livewire\Component;

class Ueber extends Component
{
    public function render()
    {
        return view('livewire.makler.ueber')
            ->title(PageMeta::title('ueber'))
            ->layout('components.layouts.site')
            ->layoutData([
                'canonical' => route('makler.ueber-uns'),
                'description' => PageMeta::description('ueber'),
            ]);
    }
}
