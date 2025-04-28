<?php

namespace App\Livewire\Pages;

use App\Enums\CompaniesEnum;
use App\Settings\PagesSettings;
use App\Traits\SplitsHtmlText;
use Livewire\Component;

class FAQ extends Component
{
    use SplitsHtmlText;

    public string $company = CompaniesEnum::Hausverwaltung->name;

    public string $text = '';


    public ?array $faqs = null;


    public function mount( PagesSettings $pagesSettings)
    {
        $this->company = CompaniesEnum::getByRoute();

        $this->text = $pagesSettings->team_introtext;

        $this->faqs = \App\Models\Question::all()->toArray();
    }


    public function render( PagesSettings $pagesSettings)
    {
        $preparedText = $this->prepareText();

        return view('livewire.pages.f-a-q', compact('pagesSettings', 'preparedText'));
    }
}
