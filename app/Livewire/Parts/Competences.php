<?php

namespace App\Livewire\Parts;

use App\Enums\CompaniesEnum;
use App\Traits\SplitsHtmlText;
use Illuminate\Support\Collection;
use Livewire\Component;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Competences extends Component
{
    use SplitsHtmlText;

    public string $company = CompaniesEnum::Hausverwaltung->name;

    public string $header;

    public string $subheader;

    public string $text;


    public ?Collection $competences = null;


    public function mount()
    {
        $this->company = CompaniesEnum::getByRoute();

        $this->competences = \App\Models\Competence::where('company', $this->company)
            ->where('on_landing', true)
            ->get();
    }


    public function render()
    {
        $preparedText = $this->prepareText();

        return view('livewire.parts.competences', compact('preparedText'));
    }
}
