<?php

namespace App\Livewire\Pages;

use App\Models\Competence;
use Livewire\Component;

class Compentence extends Component
{
    public Competence $competence;

    public string $company = '';

    public function mount(Competence $competence){
        $this->competence = $competence;
        $this->company = $this->competence->company->name;
    }


    public function render()
    {
        return view('livewire.pages.compentence',
            ['description' => $this->competence->description])
            ->title($this->competence->name);
    }
}
