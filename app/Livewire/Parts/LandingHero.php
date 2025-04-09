<?php

namespace App\Livewire\Parts;

use App\Settings\LandingpageHausverwaltung;
use Illuminate\Support\Arr;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class LandingHero extends Component
{
    public string $company;

    public string $header;

    public string $subheader;

    public string $intro;

    public array|int|null $image;

    public string $alt;

    public function render()
    {
        if(!blank($this->image)){
            $media = Media::whereIn('id', Arr::wrap($this->image))->get();
        }

        return view('livewire.parts.landing-hero', compact(['media']));
    }
}
