<?php

namespace App\Livewire\Parts;

use App\Settings\LandingpageTechnikSettings;
use Illuminate\Support\Arr;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class TechnikTextImage extends Component
{
    public function render(LandingpageTechnikSettings $settings)
    {
        $media = Media::whereIn('id', Arr::wrap($settings->text_image))->get();

        return view('livewire.parts.technik-text-image', compact('settings', 'media'));
    }
}
