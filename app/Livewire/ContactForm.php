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

    public function save()
    {
        $data = $this->validate();

        $data['data']['company'] = $this->company;
        $data['data']['token'] = Str::uuid();

        unset($data['data']['terms']);

        $request = ContactRequest::create($data['data']);

        /**
         * 0 => array:6 [▼
         * "tmpFilename" => "T6bYJq4DlWul6UOBNGwEvRV3twywsx-metaZ2VsYmVyIGtvZmZlci5qcGVn-.jpeg"
         * "name" => "gelber koffer.jpeg"
         * "extension" => "jpg"
         * "path" => "/home/vagrant/code/breal/storage/app/private/livewire-tmp/T6bYJq4DlWul6UOBNGwEvRV3twywsx-metaZ2VsYmVyIGtvZmZlci5qcGVn-.jpeg"
         * "temporaryUrl" => "http://breal.test/livewire/preview-file/T6bYJq4DlWul6UOBNGwEvRV3twywsx-metaZ2VsYmVyIGtvZmZlci5qcGVn-.jpeg?expires=1752854399&signature=816d96bdb12a65b3f36a45ae5 ▶"
         * "size" => 426682
         * ]
         */
        foreach ($data['uploads'] as $upload) {
            $request->addMedia($upload['path'])
                ->usingName($upload['name'])
                ->toMediaCollection('uploads');
        }

        $address = $data['data']['email'];
        $mail = Mail::to($address)->send(new VerificationEmail($request));


        $this->reset();

        $this->is_sent = true;
    }


    public function render(PagesSettings $pagesSettings)
    {
        return view('livewire.contact-form', compact('pagesSettings'));
    }
}
