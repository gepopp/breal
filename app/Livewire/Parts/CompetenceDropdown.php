<?php

namespace App\Livewire\Parts;

use App\Enums\CompaniesEnum;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class CompetenceDropdown extends Component
{
    public ?Collection $competences = null;

    public string $company = CompaniesEnum::Hausverwaltung->name;

    public function mount()
    {
        $this->company = CompaniesEnum::getByRoute();

        $this->competences = \App\Models\Competence::where('company', $this->company)->where('on_dropdown', true)->get();
    }


    public function render()
    {
        return view('livewire.parts.competence-dropdown');
    }
}
