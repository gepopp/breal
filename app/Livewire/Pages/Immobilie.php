<?php

namespace App\Livewire\Pages;

use App\Models\Realty;
use Livewire\Component;

class Immobilie extends Component
{
    public Realty $realty;

    public function render()
    {
        $data = $this->realty->data;

        return view('livewire.pages.immobilie', compact('data'))
            ->title($this->realty->titel)
            ->layout('components.layouts.site')
            ->layoutData([
                'canonical' => route('makler.immobilie', $this->realty),
                'description' => $this->realty->objektbeschreibung ?? $this->realty->titel,
            ]);
    }
}
