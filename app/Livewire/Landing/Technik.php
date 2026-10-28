<?php

namespace App\Livewire\Landing;

use App\Services\PageMeta;
use App\Services\StructuredData;
use App\Settings\TechnikLandingpageSettings;
use Livewire\Component;

class Technik extends Component
{
    public function render(TechnikLandingpageSettings $settings)
    {
        return view('livewire.landing.technik', compact('settings'))
            ->title(PageMeta::title('start'))
            ->layout('components.layouts.site')
            ->layoutData([
                'canonical' => route('technik.home'),
                'description' => PageMeta::description('start'),
                'schema' => [StructuredData::organizations()],
            ]);
    }
}
