<?php

namespace App\Livewire\Parts;

use App\Settings\HausverwaltungLandingpageSettings;
use Illuminate\Support\Arr;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class LandingHero extends Component
{
    public function render( HausverwaltungLandingpageSettings $settings)
    {
        $media = collect([]);

        if(!blank($settings->hero_image)){
            $media = Media::whereIn('id', Arr::wrap($settings->hero_image))->get();
        }

        return view('livewire.parts.landing-hero', compact(['media', 'settings']));
    }
}
