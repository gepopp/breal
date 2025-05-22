<?php

namespace App\Livewire\Realty;

use App\Models\Realty;
use Livewire\Component;
use Whitecube\LaravelCookieConsent\Facades\Cookies;

class Map extends Component
{
    public Realty $realty;

    public bool $is_cookie_accepted = false;

    public function mount(Realty $realty){
        $this->realty = $realty;
        $this->is_cookie_accepted = Cookies::hasConsentFor('google_maps');
    }


    public function acceptCookie(){
        Cookies::accept('optional');
        $this->is_cookie_accepted = true;
    }


    public function render()
    {
        $geo = $this->realty->data['geo']['geokoordinaten'];
        return view('livewire.realty.map', compact('geo'));
    }
}
