<?php

namespace App\Livewire;

use App\Enums\CompaniesEnum;
use App\Mail\VerificationEmail;
use App\Models\ContactRequest;
use App\Settings\PagesSettings;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Lazy;
use Livewire\Component;

class ContactForm extends Component
{
    const RECIPIENT = 'office@bereal-immobilien.at'; //'ronald@ivalu.eu';// ;

    public bool $is_sent = false;

    public bool $sidebar = true;

    public bool $address = false;

    protected string $company = '';

    public array $uploads = [];


    public array $data = [
        'subject'   => '',
        'firstname' => '',
        'lastname'  => '',
        'email'     => '',
        'phone'     => '',
        'message'   => '',
        'company'   => null,
        'terms'     => false,
        'address'   => null,
    ];


    public function mount()
    {
        if (app()->environment('local')) {
            $this->data = [
                'subject'   => 'Test-Betreff',
                'firstname' => 'Max',
                'lastname'  => 'Mustermann',
                'email'     => 'gerhard@poppgerhard.at',
                'phone'     => '0676335203',
                'message'   => 'TEXT',
                'company'   => null,
                'terms'     => true,
                'address'   => null,
            ];
        }

    }

    public function getValidationAttributes()
    {
        return [
            'data.subject'   => 'Betreff',
            'data.firstname' => 'Vorname',
            'data.lastname'  => 'Nachname',
            'data.email'     => 'E-Mail-Adresse',
            'data.phone'     => 'Telefonnummer',
            'data.message'   => 'Nachricht',
            'data.terms'     => 'Verarbeitung',
            'data.address'   => 'Adresse',
            'uploads.*'      => 'Datei',
            'uploads'        => 'Dateien',
        ];
    }


    public function rules()
    {
        return [
            'data.subject'   => ['required', 'string', 'max:255'],
            'data.firstname' => ['required', 'string', 'max:255'],
            'data.lastname'  => ['required', 'string', 'max:255'],
            'data.email'     => ['required', 'string', 'email:rfc,dns', 'max:255'],
            'data.phone'     => ['required', 'string', 'max:255'],
            'data.message'   => ['required', 'string'],
            'data.terms'     => ['required', 'accepted'],
            'data.address'   => $this->address ? ['required', 'string', 'max:255'] : ['nullable'],
            'uploads'        => ['nullable', 'array', 'max:5'],
        ];
    }

    public function submitWithoutAddress()
    {
        $this->address = false;
        $this->save();
    }


    public function submitWithAddress()
    {
        $this->address = true;
        $this->save();
    }


    public function save()
    {
        $data = $this->validate();

        $data['data']['company'] = $this->company;
        $data['data']['token'] = Str::uuid();

        unset($data['data']['terms']);

        $request = ContactRequest::create($data['data']);

        foreach ($data['uploads'] as $upload) {
            $request->addMedia($upload['path'])
                ->usingName($upload['name'])
                ->toMediaCollection('uploads', 's3');
        }

        $address = $data['data']['email'];
        Mail::to($address)->send(new VerificationEmail($request));

        $this->reset();

        $this->is_sent = true;
    }


    public function render(PagesSettings $pagesSettings)
    {
        return view('livewire.contact-form', compact('pagesSettings'));
    }
}
