<?php

namespace App\Livewire\Pages;

use App\Models\Competence;
use Livewire\Component;

class Compentence extends Component
{
    public Competence $competence;


    public function mount(Competence $competence){
        $this->competence = $competence;
    }


    public function render()
    {
        return view('livewire.pages.compentence');
    }
}
