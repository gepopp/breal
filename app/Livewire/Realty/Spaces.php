<?php

namespace App\Livewire\Realty;

use Livewire\Component;

class Spaces extends Component
{
    public array $formatted_spaces = [];

    public function mount(array $spaces)
    {

        $keys = [
            'wohnflaeche' => __('realty.spaces.living_area'),
            'freiflaeche' => __('realty.spaces.open_area'),
            'nutzflaeche' => __('realty.spaces.usable_area'),
            'anzahl_zimmer' => __('realty.spaces.rooms'),
            'anzahl_badezimmer' => __('realty.spaces.bathrooms'),
            'anzahl_sep_wc' => __('realty.spaces.wc'),
            'anzahl_balkone' => __('realty.spaces.balconies'),
        ];

        $suffixes = [
            'wohnflaeche',
            'freiflaeche',
            'nutzflaeche',
        ];

        foreach ($keys as $key => $value) {
            if (array_key_exists($key, $spaces)) {
                $this->formatted_spaces[$value] =
                    in_array($key, $suffixes) ? $spaces[$key].' m²' : $spaces[$key] ?? '--';
            }
        }

    }

    public function render()
    {
        return view('livewire.realty.spaces');
    }
}
