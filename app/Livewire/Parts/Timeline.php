<?php

namespace App\Livewire\Parts;

use App\Traits\SplitsHtmlText;
use DragonCode\Support\Facades\Helpers\Str;
use Livewire\Component;

class Timeline extends Component
{
    use SplitsHtmlText;


    public string $company;

    public string $header;

    public string $subheader;

    public string $text;


    public function render()
    {
        $preparedText = $this->prepareText();
        return view('livewire.parts.timeline', compact('preparedText'));
    }
}
