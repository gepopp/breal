<?php

namespace App\Livewire;

use App\Enums\CompaniesEnum;
use App\Mail\VerificationEmail;
use App\Models\ContactRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Livewire\Attributes\Lazy;
use Livewire\Component;

class ContactForm extends Component
{
    const RECIPIENT = 'ronald@ivalu.eu';// 'gerhard@weloveinteraction.com'; // 'l.eybel@bontus-eybel.at';

    public bool $is_sent = false;


    protected string $company = '';

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
            'data.firstname' => 'Vorname',
            'data.lastname'  => 'Nachname',
            'data.email'     => 'E-Mail-Adresse',
            'data.phone'     => 'Telefonnummer',
            'data.message'   => 'Nachricht',
            'data.terms'     => 'Verarbeitung'
        ];
    }


    public function rules()
    {
        return [
            'data.firstname' => ['required', 'string', 'max:255'],
            'data.lastname'  => ['required', 'string', 'max:255'],
            'data.email'     => ['required', 'string', 'email:rfc,dns', 'max:255'],
            'data.phone'     => ['required', 'string', 'max:255'],
            'data.message'   => ['required', 'string'],
            'data.terms'     => ['required', 'accepted']
        ];
    }

    public function save()
    {
        $data = $this->validate();
        $data['data']['company'] = $this->company;
        $data['data']['token'] = Str::uuid();
        unset($data['data']['terms']);

        $request = ContactRequest::create($data['data']);

        Mail::to($data['data']['email'])->send(new VerificationEmail($request));

        $this->reset();

        $this->is_sent = true;
    }


    public function render()
    {
        return view('livewire.contact-form');
    }
}
