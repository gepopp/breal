<?php

namespace App\Livewire\Parts;

use App\Models\Reference;
use Livewire\Component;

class References extends Component
{
    public string $company;

    public string $header;

    public string $subheader;

    public string $text;

    public function render()
    {
        $references = Reference::latest()->limit(9)->get();
        return view('livewire.parts.references', compact('references'));
    }
}
