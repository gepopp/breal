<?php

namespace App\Livewire\Sites;

use App\Services\PageMeta;
use App\Settings\PagesSettings;
use Livewire\Component;

class Imprint extends Component
{
    public function render(PagesSettings $pagesSettings)
    {
        return view('livewire.sites.imprint', compact('pagesSettings'))
            ->title(PageMeta::title('impressum'))
            ->layout('components.layouts.site')
            ->layoutData([
                'canonical' => route('impressum'),
                'description' => PageMeta::description('impressum'),
            ]);
    }
}
