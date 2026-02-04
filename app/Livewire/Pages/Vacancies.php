<?php

namespace App\Livewire\Pages;

use App\Enums\CompaniesEnum;
use App\Models\JobVacancy;
use App\Settings\PagesSettings;
use App\Settings\VacanciesSettings;
use App\Traits\SplitsHtmlText;
use Livewire\Component;

class Vacancies extends Component
{
    use SplitsHtmlText;

    public string $company = CompaniesEnum::Hausverwaltung->name;

    public string $text = '';

    public function mount(VacanciesSettings $pagesSettings)
    {
        $this->company = CompaniesEnum::getByRoute();

        $this->text = $pagesSettings->vacancies_introtext;
    }

    public function render(VacanciesSettings $pagesSettings)
    {
        $preparedText = $this->prepareText();
        $vacancies = JobVacancy::where('from', '<', now())->where('to', '>', now())->get();
        $description = __('pages.vacancies.description');

        return view('livewire.pages.vacancies',
            compact('pagesSettings', 'preparedText', 'vacancies', 'description'))
            ->title(__('pages.vacancies.title'))
            ->layout('components.layouts.site')
            ->layoutData([
                'canonical' => route($this->company === CompaniesEnum::Hausverwaltung->name ? 'hausverwaltung.karriere' : ($this->company === CompaniesEnum::Makler->name ? 'makler.karriere' : 'technik.karriere')),
                'description' => __('pages.vacancies.description'),
            ]);
    }
}
