<?php

namespace App\Livewire\Parts;

use App\Settings\LandingpageHausverwaltung;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class LandingHero extends Component
{
    public string $company;

    public string $header;

    public string $subheader;

    public string $intro;

    public int $image;

    public string $alt;

    public function render()
    {
        $media = Media::whereId($this->image)->first();

        return view('livewire.parts.landing-hero', compact(['media']));
    }
}
