<?php

namespace App\Livewire\Pages;

use App\Models\Competence;
use App\Services\PageMeta;
use Livewire\Component;

class Compentence extends Component
{
    public Competence $competence;

    public string $company = '';

    public function mount(Competence $competence)
    {
        $this->competence = $competence;
        $this->company = $this->competence->company->name;
    }

    public function render()
    {
        return view('livewire.pages.compentence',
            ['description' => $this->competence->description])
            ->title(PageMeta::compose($this->competence->name))
            ->layout('components.layouts.site')
            ->layoutData([
                'canonical' => route($this->company === 'Hausverwaltung' ? 'hausverwaltung.leistung' : ($this->company === 'Makler' ? 'makler.leistung' : 'technik.leistung'), $this->competence),
                'description' => PageMeta::excerpt($this->competence->description),
            ]);
    }
}
