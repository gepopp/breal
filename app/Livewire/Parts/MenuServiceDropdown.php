<?php

namespace App\Livewire\Parts;

use App\Enums\CompaniesEnum;
use App\Models\Service;
use Livewire\Component;

class MenuServiceDropdown extends Component
{
    public string $company = CompaniesEnum::Hausverwaltung->name;

    public function mount()
    {
        $this->company = CompaniesEnum::getByRoute();
    }

    public function render()
    {
        return view('livewire.parts.menu-service-dropdown', [
            'items' => Service::all()->map(fn (Service $service): array => [
                'id' => $service->id,
                'name' => $service->translatedFor('name'),
                'link' => $service->link,
            ])->all(),
        ]);
    }
}
