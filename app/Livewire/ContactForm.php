<?php

namespace App\Livewire;

use App\Enums\CompaniesEnum;
use App\Mail\VerificationEmail;
use App\Models\ContactRequest;
use App\Settings\PagesSettings;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Component;

class ContactForm extends Component
{
    const RECIPIENT = 'office@bereal-immobilien.at';

    public bool $is_sent = false;

    public bool $sidebar = true;

    public bool $address = true;

    /**
     * The business area (name of CompaniesEnum) of the page the form is on.
     */
    public string $company = '';

    public array $uploads = [];

    public array $data = [
        'subject' => '',
        'firstname' => '',
        'lastname' => '',
        'email' => '',
        'phone' => '',
        'message' => '',
        'company' => null,
        'terms' => false,
        'address' => null,
    ];

    public function mount()
    {
        $this->company = CompaniesEnum::getByRoute();
    }

    public function getValidationAttributes()
    {
        return [
            'data.subject' => __('contact.validation.attributes.subject'),
            'data.firstname' => __('contact.validation.attributes.firstname'),
            'data.lastname' => __('contact.validation.attributes.lastname'),
            'data.email' => __('contact.validation.attributes.email'),
            'data.phone' => __('contact.validation.attributes.phone'),
            'data.message' => __('contact.validation.attributes.message'),
            'data.terms' => __('contact.validation.attributes.terms'),
            'data.address' => __('contact.validation.attributes.address'),
            'uploads.*' => __('contact.validation.attributes.file'),
            'uploads' => __('contact.validation.attributes.files'),
        ];
    }

    public function rules()
    {
        return [
            'data.subject' => ['required', 'string', 'max:255'],
            'data.firstname' => ['required', 'string', 'max:255'],
            'data.lastname' => ['required', 'string', 'max:255'],
            'data.email' => ['required', 'string', 'email:rfc,dns', 'max:255'],
            'data.phone' => ['required', 'string', 'max:255'],
            'data.message' => ['required', 'string'],
            'data.terms' => ['required', 'accepted'],
            'data.address' => $this->address ? ['required', 'string', 'max:255'] : ['nullable'],
            'uploads' => ['nullable', 'array', 'max:5'],
        ];
    }

    public function submitWithoutAddress()
    {
        $this->validate();
        $this->address = false;
        $this->save();
    }

    public function submitWithAddress()
    {
        $this->validate();
        $this->address = true;
        $this->save();
    }

    public function save()
    {
        $data = $this->validate();

        $data['data']['company'] = CompaniesEnum::tryFrom($this->company)?->name ?? CompaniesEnum::Hausverwaltung->name;
        $data['data']['token'] = Str::uuid();
        $data['data']['locale'] = app()->getLocale();

        unset($data['data']['terms']);

        $request = ContactRequest::create($data['data']);

        foreach ($data['uploads'] as $upload) {
            $request->addMedia($upload['path'])
                ->usingName($upload['name'])
                ->toMediaCollection('uploads', 's3_private');
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
