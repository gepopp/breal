<?php

namespace App\Livewire\Makler\Parts;

use App\Settings\LandingpageMaklerSettings;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Hero extends Component
{
    public function render( LandingpageMaklerSettings $settings)
    {
        $images = Media::whereIn('id', $settings->hero_images)->get();

        return view('livewire.makler.parts.hero', compact('settings', 'images'));
    }
}
