<?php

namespace App\Livewire\Makler;

use App\Services\PageMeta;
use Livewire\Component;

class Immobiliensuche extends Component
{
    public function render()
    {
        return view('livewire.makler.immobiliensuche')
            ->title(PageMeta::title('immobiliensuche'))
            ->layout('components.layouts.site')
            ->layoutData([
                'canonical' => route('makler.immobiliensuche'),
                'description' => PageMeta::description('immobiliensuche'),
            ]);
    }
}
