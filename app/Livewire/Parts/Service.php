<?php

namespace App\Livewire\Parts;

use App\Traits\SplitsHtmlText;
use Livewire\Component;

class Service extends Component
{
    use SplitsHtmlText;
    public string $company;

    public string $header;
    public string $subheader;

    public string $text;

    public function render()
    {
        $preparedText = $this->prepareText();
        return view('livewire.parts.service', compact('preparedText'));
    }
}
