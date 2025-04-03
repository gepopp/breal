<?php

namespace App\Livewire\Parts;

use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class About extends Component
{
    public string $company;

    public string $header;

    public string $subheader;

    public string $text;

    public int $image;

    public string $alt;


    public function render()
    {
        $media = Media::whereId($this->image)->first();

        return view('livewire.parts.about', compact('media'));
    }
}
