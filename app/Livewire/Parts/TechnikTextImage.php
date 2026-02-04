<?php

namespace App\Livewire\Parts;

use App\Settings\TechnikLandingpageSettings;
use Illuminate\Support\Arr;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class TechnikTextImage extends Component
{
    public function render(TechnikLandingpageSettings $settings)
    {
        $media = Media::whereIn('id', Arr::wrap($settings->text_image))->get();

        return view('livewire.parts.technik-text-image', compact('settings', 'media'));
    }
}
