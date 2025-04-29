<?php

namespace App\Livewire\Makler\Parts;

use App\Models\Realty;
use App\Settings\LandingpageMaklerSettings;
use App\Traits\SplitsHtmlText;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Hero extends Component
{
    use SplitsHtmlText;

    public string $text = '';

    public function mount(LandingpageMaklerSettings $settings)
    {
        $this->text = $settings->intro_description;
    }

    public function render(LandingpageMaklerSettings $settings)
    {
        $images = Media::whereIn('id', $settings->hero_images)->get();
        $realEstates = Realty::all();
        $preparedText = $this->prepareText();

        return view('livewire.makler.parts.hero', compact('settings', 'images', 'realEstates', 'preparedText'));
    }
}
