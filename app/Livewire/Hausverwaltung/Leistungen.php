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

    public function mount( PagesSettings $pagesSettings)
    {
        $this->company = CompaniesEnum::getByRoute();

        $this->text = $pagesSettings->team_introtext;

        $this->leistungen = Competence::where('company', $this->company)->get();
    }

    public function render(PagesSettings $pagesSettings)
    {
        $text = strtolower( $this->company ) . '_leistungen_introtext';
        $preparedText = $this->prepareText($pagesSettings->$text);
        $description = 'Von allgemeiner Immobilienverwaltung über WEG- und Gewerbeimmobilienbetreuung bis zum Baumanagement: be real bietet maßgeschneiderte Services für Eigentümer & Investoren in Wien & ganz Österreich.';
        return view('livewire.hausverwaltung.leistungen',
            compact('pagesSettings', 'preparedText', 'description'))
            ->title('Unsere Leistungen | be real – Hausverwaltungen, WEG, Bau- & Gewerbeobjekte');
    }
}
