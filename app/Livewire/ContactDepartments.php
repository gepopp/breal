<?php

namespace App\Livewire;

use App\Enums\CompaniesEnum;
use App\Models\Department;
use App\Settings\ContactSettings;
use Livewire\Component;

class ContactDepartments extends Component
{
    /**
     * Company whose departments are listed; null lists the departments of all companies.
     */
    public ?string $company = CompaniesEnum::Hausverwaltung->name;

    public bool $withIntro = true;

    public function render(ContactSettings $pagesSettings)
    {
        $departments = Department::query()
            ->when($this->company !== null, fn ($query) => $query->where('company', $this->company))
            ->get();

        return view('livewire.contact-departments', compact('departments', 'pagesSettings'));
    }
}
