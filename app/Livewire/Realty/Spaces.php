<?php

namespace App\Livewire\Realty;

use Illuminate\Support\Collection;
use Livewire\Component;

class Spaces extends Component
{
    public array $formatted_spaces = [];


    public function mount(array $spaces)
    {

        $keys = [
            "wohnflaeche"       => "Wohnfläche",
            "freiflaeche"       => "Freiflaeche",
            "nutzflaeche"       => "Nutzfläche",
            "anzahl_zimmer"     => "Zimmer",
            "anzahl_badezimmer" => "Badezimmer",
            "anzahl_sep_wc"     => "WC",
            "anzahl_balkone"    => "Balkone",
        ];

        $suffixes = [
            "wohnflaeche",
            "freiflaeche",
            "nutzflaeche",
        ];

        foreach ($keys as $key => $value) {
            if (array_key_exists($key, $spaces)) {
                $this->formatted_spaces[$value] =
                    in_array($key, $suffixes) ? $spaces[$key] . ' m²' : $spaces[$key] ?? '--';
            }
        }

    }


    public function render()
    {
        return view('livewire.realty.spaces');
    }
}
