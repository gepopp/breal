<?php

namespace App\Livewire\Pages;

use App\Enums\CompaniesEnum;
use App\Settings\PagesSettings;
use App\Traits\SplitsHtmlText;
use Livewire\Component;

class Team extends Component
{
    use SplitsHtmlText;

    public string $company = CompaniesEnum::Hausverwaltung->name;

    public string $text = '';

    public function mount(PagesSettings $pagesSettings)
    {
        $this->company = CompaniesEnum::getByRoute();

        $this->text = $pagesSettings->team_introtext;
    }

    public function render(PagesSettings $pagesSettings)
    {
        $preparedText = $this->prepareText();
        $description = 'Lernen Sie das Team von be real kennen: erfahrene Hausverwalter:innen, Jurist:innen & Immobilienexpert:innen, die mit Leidenschaft & Klarheit für Ihre Immobilie arbeiten.';
        return view('livewire.pages.team',
            compact('pagesSettings', 'preparedText', 'description'))
            ->title('Unser Team | be real Immobilien – Persönlich & kompetent für Sie da');
    }
}
