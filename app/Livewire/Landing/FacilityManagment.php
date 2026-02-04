<?php

namespace App\Livewire\Landing;

use App\Enums\CompaniesEnum;
use App\Settings\HausverwaltungLandingpageSettings;
use Livewire\Component;

class FacilityManagment extends Component
{
    public string $company = CompaniesEnum::Hausverwaltung->name;

    public function render(HausverwaltungLandingpageSettings $settings)
    {
        return view('livewire.landing.facility-managment', compact('settings'))
            ->title(__('facility_management.title'))
            ->layout('components.layouts.site')
            ->layoutData([
                'canonical' => route('hausverwaltung.home'),
                'description' => __('facility_management.description'),
            ]);
    }
}
