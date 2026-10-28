<?php

namespace App\Livewire\Pages;

use App\Settings\PagesSettings;
use Livewire\Component;

class Policy extends Component
{
    public function render(PagesSettings $pagesSettings)
    {
        return view('livewire.pages.policy', compact('pagesSettings'))
            ->title(__('pages.policy.title'))
            ->layout('components.layouts.site')
            ->layoutData([
                'canonical' => route('datenschutz'),
                'description' => __('pages.policy.description'),
            ]);
    }
}
