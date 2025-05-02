<?php

namespace App\Livewire\Subparts;

use App\Models\Realty;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Lazy;
use Livewire\Component;


#[Lazy]
class RealtyThumbnail extends Component
{
    public Realty $realty;

    public string $titleimage = '';

    public function mount(Realty $realty){
        $this->realty = $realty;

        $data = Storage::disk('public')->get($realty->path);
        $data = json_decode($data, true);
        $files = $data['anhaenge']['anhang'];

        $titelimage = array_filter($files, function ($value) {
            return $value['@attributes']['gruppe'] == 'TITELBILD';
        });

        if(count($titelimage)){
            $titelimage = array_shift($titelimage);
            $this->titleimage = $titelimage['daten']['pfad'];
        }
    }


    public function render()
    {
        return view('livewire.subparts.realty-thumbnail');
    }
}
