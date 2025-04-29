<?php

namespace App\Livewire\Parts;

use App\Settings\LandingpageMaklerSettings;
use App\Traits\SplitsHtmlText;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MaklerCTA extends Component
{
    use SplitsHtmlText;


    public function render( LandingpageMaklerSettings $settings )
    {
        $media = Media::whereId($settings->cta_image)->first();

        return view('livewire.parts.makler-c-t-a', compact('settings', 'media'));
    }
}
