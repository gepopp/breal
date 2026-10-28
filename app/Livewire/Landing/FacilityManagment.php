<?php

namespace App\Livewire\Landing;

use App\Enums\CompaniesEnum;
use App\Services\PageMeta;
use App\Settings\HausverwaltungLandingpageSettings;
use Livewire\Component;

class FacilityManagment extends Component
{
    public string $company = CompaniesEnum::Hausverwaltung->name;

    public function render(HausverwaltungLandingpageSettings $settings)
    {
        return view('livewire.landing.facility-managment', compact('settings'))
            ->title(PageMeta::title('start'))
            ->layout('components.layouts.site')
            ->layoutData([
                'canonical' => route('hausverwaltung.home'),
                'description' => PageMeta::description('start'),
            ]);
    }
}
