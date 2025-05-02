<?php

namespace App\Livewire\Parts;

use App\Settings\LandingpageHausverwaltung;
use App\Traits\SplitsHtmlText;
use Illuminate\Support\Collection;
use Livewire\Component;

class ServiceV2 extends Component
{
    use SplitsHtmlText;


    public ?array $services = null;


    public function mount()
    {
        $this->services = \App\Models\Service::all()->toArray();
    }

    public function render( LandingpageHausverwaltung $settings)
    {
        $preparedText = $this->prepareText($settings->service_introtext);

        return view('livewire.parts.service-v2', compact('preparedText', 'settings'));
    }
}
