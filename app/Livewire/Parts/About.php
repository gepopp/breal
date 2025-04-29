<?php

namespace App\Livewire\Parts;

use App\Settings\LandingpageHausverwaltung;
use App\Traits\SplitsHtmlText;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class About extends Component
{
    use SplitsHtmlText;



    public function render( LandingpageHausverwaltung $settings )
    {
        $media = Media::whereId($settings->about_image)->first();

        return view('livewire.parts.about', compact('media', 'settings'));
    }
}
