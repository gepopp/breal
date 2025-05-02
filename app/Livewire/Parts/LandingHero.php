<?php

namespace App\Livewire\Parts;

use App\Models\Settings;
use App\Settings\LandingpageHausverwaltung;
use Illuminate\Support\Arr;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class LandingHero extends Component
{
    public function render( LandingpageHausverwaltung $settings)
    {
        if(!blank($settings->hero_image)){
            $media = Media::whereIn('id', Arr::wrap($settings->hero_image))->get();
        }

        return view('livewire.parts.landing-hero', compact(['media', 'settings']));
    }
}
