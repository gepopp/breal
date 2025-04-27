<?php

namespace App\Livewire\Hausverwaltung;

use App\Settings\PagesSettings;
use App\Traits\SplitsHtmlText;
use Illuminate\Support\Collection;
use Livewire\Attributes\Url;
use Livewire\Component;

class Service extends Component
{
    use SplitsHtmlText;


    public $text;

    public ?Collection $services = null;

    #[Url]
    public ?string $tab = '';

    public function mount(PagesSettings $pagesSettings): void
    {
        $this->text = $pagesSettings->services_introtext;
        $this->services = \App\Models\Service::all();
        $this->tab = $this->services->first()->slug;
    }


    public function render(PagesSettings $pagesSettings)
    {
        $preparedText = $this->prepareText();

        return view('livewire.hausverwaltung.service', compact('pagesSettings', 'preparedText'));
    }
}
