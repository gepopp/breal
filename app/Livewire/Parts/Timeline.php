<?php

namespace App\Livewire\Parts;

use App\Settings\HausverwaltungLandingpageSettings;
use App\Traits\SplitsHtmlText;
use DragonCode\Support\Facades\Helpers\Str;
use Livewire\Component;

class Timeline extends Component
{
    use SplitsHtmlText;

    public function render( HausverwaltungLandingpageSettings $settings )
    {
        $preparedText = $this->prepareText($settings->timeline_intro);
        return view('livewire.parts.timeline', compact('preparedText', 'settings'));
    }
}
