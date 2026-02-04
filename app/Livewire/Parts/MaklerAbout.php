<?php

namespace App\Livewire\Parts;

use App\Settings\MaklerLandingpageSettings;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MaklerAbout extends Component
{
    public function render( MaklerLandingpageSettings $settings )
    {
        $image = Media::whereId( $settings->about_image )->first();

        return view('livewire.parts.makler-about', compact('settings', 'image' ));
    }
}
