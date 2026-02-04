<?php

namespace App\Livewire\Sites;

use App\Settings\PagesSettings;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Imprint extends Component
{
    public function render(PagesSettings $pagesSettings)
    {
        return view('livewire.sites.imprint', compact('pagesSettings'))
            ->title(__('legal.imprint_title'))
            ->layout('components.layouts.site')
            ->layoutData([
                'canonical' => route('impressum'),
                'description' => __('pages.imprint.description'),
            ]);
    }
}
