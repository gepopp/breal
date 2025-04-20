<?php

namespace App\Livewire\Parts;

use App\Traits\SplitsHtmlText;
use Illuminate\Support\Collection;
use Livewire\Component;

class ServiceV2 extends Component
{
    use SplitsHtmlText;

    public string $company;

    public string $header;

    public string $subheader;

    public string $text;


    public ?array $services = null;


    public function mount()
    {
        $this->services = \App\Models\Service::all()->toArray();
    }

    public function render()
    {
        $preparedText = $this->prepareText();

        return view('livewire.parts.service-v2', compact('preparedText'));
    }
}
