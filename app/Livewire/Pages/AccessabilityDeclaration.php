<?php

namespace App\Livewire\Pages;

use App\Services\PageMeta;
use App\Settings\PagesSettings;
use Livewire\Component;

class AccessabilityDeclaration extends Component
{
    public function render(PagesSettings $pagesSettings)
    {
        return view('livewire.pages.accessability-declaration', compact('pagesSettings'))
            ->title(PageMeta::title('barrierefreiheit'))
            ->layout('components.layouts.site')
            ->layoutData([
                'canonical' => route('barrierefreiheit'),
                'description' => PageMeta::description('barrierefreiheit'),
            ]);
    }
}
