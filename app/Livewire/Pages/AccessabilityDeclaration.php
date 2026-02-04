<?php

namespace App\Livewire\Pages;

use App\Settings\PagesSettings;
use Livewire\Component;

class AccessabilityDeclaration extends Component
{
    public function render(PagesSettings $pagesSettings)
    {
        return view('livewire.pages.accessability-declaration', compact('pagesSettings'))
            ->title(__('legal.accessibility_title'))
            ->layout('components.layouts.site')
            ->layoutData([
                'canonical' => route('barrierefreiheit'),
                'description' => __('pages.accessibility.description'),
            ]);
    }
}
