<?php

namespace App\Livewire\Pages;

use App\Enums\CompaniesEnum;
use App\Models\JobVacancy;
use App\Settings\PagesSettings;
use App\Traits\SplitsHtmlText;
use Livewire\Component;

class Vacancies extends Component
{
    use SplitsHtmlText;


    public string $company = CompaniesEnum::Hausverwaltung->name;

    public string $text = '';

    public function mount( PagesSettings $pagesSettings)
    {
        $this->company = CompaniesEnum::getByRoute();

        $this->text = $pagesSettings->vacancies_introtext;
    }



    public function render(PagesSettings $pagesSettings)
    {
        $preparedText = $this->prepareText();
        $vacancies = JobVacancy::where('from', '<',  now())->where('to', '>', now())->get();

        return view('livewire.pages.vacancies', compact('pagesSettings', 'preparedText', 'vacancies'));
    }
}
