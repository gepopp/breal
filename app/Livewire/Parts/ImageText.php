<?php

namespace App\Livewire\Parts;

use Illuminate\Support\Arr;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ImageText extends Component
{
    public string $text;
    public int|array|null  $image;
    public string $alt;

    public function render()
    {
        $media = [];

        if(!blank($this->image)){
            $media = Media::whereIn('id', Arr::wrap($this->image))->get();
        }

        return view('livewire.parts.image-text', compact('media'));
    }
}
