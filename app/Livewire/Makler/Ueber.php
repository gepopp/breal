<?php

namespace App\Livewire\Makler;

use Livewire\Component;

class Ueber extends Component
{
    public function render()
    {
        return view('livewire.makler.ueber')
            ->title(__('pages.ueber.title'))
            ->layout('components.layouts.site')
            ->layoutData([
                'canonical' => route('makler.ueber-uns'),
                'description' => __('pages.ueber.description'),
            ]);
    }
}
