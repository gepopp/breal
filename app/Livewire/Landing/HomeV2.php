<?php

namespace App\Livewire\Landing;

use App\Enums\CompaniesEnum;
use App\Settings\LandingpageHausverwaltung;
use Livewire\Component;

class HomeV2 extends Component
{
    public string $company = CompaniesEnum::Hausverwaltung->name;

    public function render(LandingpageHausverwaltung $settings)
    {
        $description = 'Hausverwaltung, der Sie vertrauen können: Mit Kompetenz, Nachhaltigkeit & persönlicher Betreuung. be real kümmert sich um Ihre Zinshäuser, Wohnungseigentum & Gewerbeimmobilien als wäre es unser eigenes.';

        return view('livewire.landing.home-v2', compact('settings', 'description'))
            ->title('be real Hausverwaltung in Wien mit Weitblick & Persönlichkeit ');
    }
}
