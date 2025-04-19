<?php

namespace App\Livewire\Parts;

use App\Models\Reference;
use App\Traits\SplitsHtmlText;
use Livewire\Component;

class References extends Component
{
    use SplitsHtmlText;

    public string $company;

    public string $header;

    public string $subheader;

    public string $text;

    public function render()
    {
        $references = Reference::latest()->limit(9)->get();
        $preparedText = $this->prepareText();
        return view('livewire.parts.references', compact('references', 'preparedText'));
    }
}
