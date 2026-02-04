<?php

namespace App\Livewire\Landing;

use App\Enums\CompaniesEnum;
use App\Settings\HausverwaltungLandingpageSettings;
use Livewire\Component;

class HomeV2 extends Component
{
    public string $company = CompaniesEnum::Hausverwaltung->name;

    public function render(HausverwaltungLandingpageSettings $settings)
    {
        $description = __('landing.home.description');

        return view('livewire.landing.home-v2', compact('settings', 'description'))
            ->title(__('landing.home.title'))
            ->layout('components.layouts.site')
            ->layoutData([
                'canonical' => route('hausverwaltung.home'),
                'description' => __('landing.home.description'),
            ]);
    }
}
