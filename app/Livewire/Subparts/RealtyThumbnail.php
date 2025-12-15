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


        dd($realty->data);


        $files = $realty->data['anhaenge']['anhang'];

        $titelimage = array_filter($files, function ($value) {
            return $value['@attributes']['gruppe'] == 'TITELBILD';
        });

        if(count($titelimage)){
            $titelimage = array_shift($titelimage);
            $this->titleimage = $titelimage['daten']['pfad'];
        }
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div class="w-24 aspect-video animate-pulse bg-gray-200 flex items-center justify-center">
            <svg class="animate-spin -ml-1 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
        HTML;
    }

    public function render()
    {
        return view('livewire.subparts.realty-thumbnail');
    }
}
