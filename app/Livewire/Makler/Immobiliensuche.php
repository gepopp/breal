<?php

namespace App\Livewire\Makler;

use Livewire\Component;

class Immobiliensuche extends Component
{
    public function render()
    {
        return view('livewire.makler.immobiliensuche')
            ->title(__('pages.immobiliensuche.title'))
            ->layout('components.layouts.site')
            ->layoutData([
                'canonical' => route('makler.immobiliensuche'),
                'description' => __('pages.immobiliensuche.description'),
            ]);
    }
}
