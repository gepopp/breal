<?php

namespace App\Livewire\Parts;

use App\Settings\TechnikLandingpageSettings;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class TechnikAbout extends Component
{
    public function render( TechnikLandingpageSettings $settings)
    {
        $media = Media::whereId( $settings->about_image )->first();

        return view('livewire.parts.technik-about', compact('media', 'settings'));
    }
}
