<?php

namespace App\Livewire\Parts;

use App\Enums\CompaniesEnum;
use App\Services\CompanyInfo;
use App\Settings\HausverwaltungLandingpageSettings;
use Livewire\Component;

class Contact extends Component
{

    public function render( HausverwaltungLandingpageSettings $settings )
    {
        return view('livewire.parts.contact', ['settings' => $settings, 'introtext' => $this->introtext($settings)]);
    }

    /**
     * The intro text is shared by all landing pages and names the company; on the Makler and
     * Technik pages it has to name that section instead of "be real Immobilienmanagement".
     */
    private function introtext(HausverwaltungLandingpageSettings $settings): string
    {
        $company = CompaniesEnum::getByRoute();

        if ($company === CompaniesEnum::Hausverwaltung->name) {
            return $settings->contact_introtext;
        }

        return str_replace('be real Immobilienmanagement', CompanyInfo::section($company)['name'], $settings->contact_introtext);
    }
}
