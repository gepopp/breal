<?php

namespace App\Livewire\Parts;

use App\Traits\SplitsHtmlText;
use Illuminate\Support\Collection;
use Livewire\Component;

class Competences extends Component
{
    use SplitsHtmlText;

    public string $company;

    public string $header;

    public string $subheader;

    public string $text;


    public ?Collection $competences = null;


    public function mount()
    {
        $this->competences = \App\Models\Competence::limit(9)->get();
    }


    public function render()
    {
        $preparedText = $this->prepareText();

        return view('livewire.parts.competences', compact('preparedText'));
    }
}
