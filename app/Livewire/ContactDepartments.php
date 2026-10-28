<?php

namespace App\Livewire;

use App\Enums\CompaniesEnum;
use App\Models\Department;
use App\Settings\ContactSettings;
use Livewire\Component;

class ContactDepartments extends Component
{
    public string $company = CompaniesEnum::Hausverwaltung->name;

    public bool $withIntro = true;

    public function render(ContactSettings $pagesSettings)
    {
        $departments = Department::where('company', $this->company)->get();

        return view('livewire.contact-departments', compact('departments', 'pagesSettings'));
    }
}
