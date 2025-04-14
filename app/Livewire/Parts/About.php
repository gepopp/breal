<?php

namespace App\Livewire\Parts;

use App\Traits\SplitsHtmlText;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class About extends Component
{
    use SplitsHtmlText;

    public string $company;

    public string $header;

    public string $subheader;

    public string $text;

    public int|array $image;

    public string $alt;


    public ?string $video;


    public function render()
    {
        $media = Media::whereId($this->image)->first();

        return view('livewire.parts.about', compact('media'));
    }
}
