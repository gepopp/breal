<?php

namespace App\Livewire\Hausverwaltung;

use App\Enums\CompaniesEnum;
use App\Models\Competence;
use App\Settings\PagesSettings;
use App\Traits\SplitsHtmlText;
use Illuminate\Support\Collection;
use Livewire\Component;

class Leistungen extends Component
{
    use SplitsHtmlText;

    public string $company = CompaniesEnum::Hausverwaltung->name;

    public ?Collection $leistungen = null;

    public function mount(PagesSettings $pagesSettings)
    {
        $this->company = CompaniesEnum::getByRoute();

        $this->text = $pagesSettings->team_introtext;

        $this->leistungen = Competence::where('company', $this->company)->get();
    }

    public function render(PagesSettings $pagesSettings)
    {
        $text = strtolower($this->company).'_leistungen_introtext';
        $preparedText = $this->prepareText($pagesSettings->$text);

        return view('livewire.hausverwaltung.leistungen',
            compact('pagesSettings', 'preparedText'))
            ->title(__('pages.leistungen.title'))
            ->layout('components.layouts.site')
            ->layoutData([
                'canonical' => route($this->company === CompaniesEnum::Hausverwaltung->name ? 'hausverwaltung.leistungen' : ($this->company === CompaniesEnum::Makler->name ? 'makler.leistungen' : 'technik.leistungen')),
                'description' => __('pages.leistungen.description'),
            ]);
    }
}
