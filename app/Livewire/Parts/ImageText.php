<?php

namespace App\Livewire\Parts;

use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ImageText extends Component
{
    public string $text;

    public int|array  $image;

    public string $alt;

    public function render()
    {
        $media = Media::whereId($this->image)->first();

        return view('livewire.parts.image-text', compact('media'));
    }
}
