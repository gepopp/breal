<?php

namespace App\Livewire;

use App\Models\Question;
use Livewire\Component;

class FAQSingle extends Component
{
    public \App\Models\Question $faq;


    public function mount(string $slug){
        $this->faq = Question::findBySlug($slug);
    }


    public function render()
    {
        return view('livewire.f-a-q-single');
    }
}
