<?php

namespace App\Livewire;

use App\Enums\CompaniesEnum;
use App\Models\Department;
use App\Settings\PagesSettings;
use Livewire\Component;

class ContactDepartments extends Component
{
    public string $comapny = CompaniesEnum::Hausverwaltung->name;


    public function render( PagesSettings $pagesSettings)
    {
        $departments = Department::where('company', $this->comapny)->get();

        return view('livewire.contact-departments', compact('departments', 'pagesSettings'));
    }
}
