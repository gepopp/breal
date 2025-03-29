<?php

namespace App\Livewire;

use App\Enums\CompaniesEnum;
use App\Models\Department;
use Livewire\Component;

class ContactDepartments extends Component
{
    public string $comapny = CompaniesEnum::Hausverwaltung->name;


    public function render()
    {
        $departments = Department::where('company', $this->comapny)->get();

        return view('livewire.contact-departments', compact('departments'));
    }
}
