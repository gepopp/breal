<?php

namespace App\Livewire\Landing;

use App\Enums\CompaniesEnum;
use App\Services\PageMeta;
use App\Settings\HausverwaltungLandingpageSettings;
use Livewire\Component;

class HomeV2 extends Component
{
    public string $company = CompaniesEnum::Hausverwaltung->name;

    public function render(HausverwaltungLandingpageSettings $settings)
    {
        $description = PageMeta::description('start');

        return view('livewire.landing.home-v2', compact('settings', 'description'))
            ->title(PageMeta::title('start'))
            ->layout('components.layouts.site')
            ->layoutData([
                'canonical' => route('hausverwaltung.home'),
                'description' => PageMeta::description('start'),
            ]);
    }
}
