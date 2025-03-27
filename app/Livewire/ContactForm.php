<?php

namespace App\Livewire;

use App\Enums\CompaniesEnum;
use App\Models\ContactRequest;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class ContactForm extends Component
{
    const RECIPIENT = 'l.eybel@bontus-eybel.at';


    public array $data = [
        'firstname' => '',
        'lastname'  => '',
        'email'     => '',
        'phone'     => '',
        'message'   => '',
        'company'   => null,
        'terms'     => false
    ];

    public function getValidationAttributes()
    {
        return [
            'firstname' => 'Vorname',
            'lastname'  => 'Nachname',
            'email'     => 'E-Mail-Adresse',
            'phone'     => 'Telefonnummer',
            'message'   => 'Nachricht',
            'terms'     => 'Verarbeitung'
        ];
    }


    public function rules()
    {
        return [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname'  => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email:rfc,dns', 'max:255'],
            'phone'     => ['required', 'string', 'max:255'],
            'message'   => ['required', 'string'],
            'terms'     => ['required', 'accepted']
        ];
    }


    public function save()
    {
        $data = $this->validate();
        $data['data']['company'] = CompaniesEnum::getByRoute();
        unset($data['data']['terms']);

        ContactRequest::create($data);

    }


    public function render()
    {
        return view('livewire.contact-form');
    }
}
