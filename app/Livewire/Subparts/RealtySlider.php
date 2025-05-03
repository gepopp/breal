<?php

namespace App\Livewire\Subparts;

use App\Models\Realty;
use Livewire\Attributes\Computed;
use Livewire\Component;

class RealtySlider extends Component
{
    public Realty $realty;


    #[Computed]
    public function realtyImages(){
        $files = $this->realty->data['anhaenge']['anhang'];
        $images = array_filter($files, fn($file) => in_array($file['format'], ['jpeg', 'png', 'jpg']));
        $urls = [];
        foreach($images as $image){
            $urls[] = $image['daten']['pfad'];
        }
        return $urls;
    }


    public function render()
    {
        return view('livewire.subparts.realty-slider');
    }
}
