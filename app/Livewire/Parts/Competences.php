<?php

namespace App\Livewire\Parts;

use App\Models\Competence;
use App\Enums\CompaniesEnum;
use App\Settings\HausverwaltungLandingpageSettings;
use App\Traits\SplitsHtmlText;
use Illuminate\Support\Collection;
use Livewire\Component;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Competences extends Component
{
    use SplitsHtmlText;

    public ?Collection $competences = null;

    public string $company = CompaniesEnum::Hausverwaltung->name;

    public function mount()
    {
        $this->competences =
            Competence::where('company', $this->company)
            ->where('on_landing', true)
            ->get();
    }


    public function render( HausverwaltungLandingpageSettings $settings )
    {
        $this->company = CompaniesEnum::getByRoute();

        $preparedText = $this->prepareText( $settings->competence_introtext );

        return view('livewire.parts.competences', compact('preparedText', 'settings'));
    }
}
