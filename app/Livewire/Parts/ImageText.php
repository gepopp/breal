<?php

namespace App\Livewire\Parts;

use App\Settings\HausverwaltungLandingpageSettings;
use Illuminate\Support\Arr;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ImageText extends Component
{

    public function render( HausverwaltungLandingpageSettings $settings )
    {
        $media = [];

        if(!blank($settings->text_image)){
            $media = Media::whereIn('id', Arr::wrap($settings->text_image))->get();
        }

        return view('livewire.parts.image-text', compact('media', 'settings'));
    }
}
