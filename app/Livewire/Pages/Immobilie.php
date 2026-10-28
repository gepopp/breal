<?php

namespace App\Livewire\Pages;

use App\Models\Realty;
use App\Services\PageMeta;
use Livewire\Component;

class Immobilie extends Component
{
    public Realty $realty;

    public function render()
    {
        $data = $this->realty->data;

        return view('livewire.pages.immobilie', compact('data'))
            ->title(PageMeta::compose($this->realty->title))
            ->layout('components.layouts.site')
            ->layoutData([
                'canonical' => route('makler.immobilie', $this->realty),
                'description' => PageMeta::excerpt($data['freitexte']['objektbeschreibung'] ?? $this->realty->beschreibung ?? $this->realty->title),
            ]);
    }
}
