<?php

namespace App\Livewire\Landing;

use App\Enums\CompaniesEnum;
use App\Settings\LandingpageHausverwaltung;
use Livewire\Component;

class FacilityManagment extends Component
{
    public string $company = CompaniesEnum::Hausverwaltung->name;


    public function render( LandingpageHausverwaltung $settings)
    {
        return view('livewire.landing.facility-managment', compact('settings'));
    }
}
