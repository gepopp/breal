<?php

namespace App\Livewire\Pages;

use App\Services\PageMeta;
use App\Settings\LegalSettings;
use Livewire\Component;

class Policy extends Component
{
    public function render(LegalSettings $pagesSettings)
    {
        return view('livewire.pages.policy', compact('pagesSettings'))
            ->title(PageMeta::title('datenschutz'))
            ->layout('components.layouts.site')
            ->layoutData([
                'canonical' => route('datenschutz'),
                'description' => PageMeta::description('datenschutz'),
            ]);
    }
}
