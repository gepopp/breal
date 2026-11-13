<?php

namespace App\Livewire;

use App\Enums\CompaniesEnum;
use App\Mail\VerificationEmail;
use App\Models\ContactRequest;
use App\Settings\PagesSettings;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use RuntimeException;
use Throwable;

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

    /**
     * @param  string  $subject  Prefilled subject, e.g. the FAQ question the inquiry is about.
     */
    public function mount(string $subject = '')
    {
        // A form that is loaded lazily is mounted in a later request (the Livewire update route), where the
        // route no longer tells the area; the page passes it in then.
        if ($this->company === '') {
            $this->company = CompaniesEnum::getByRoute();
        }

        if ($subject !== '') {
            $this->data['subject'] = Str::limit($subject, 255, '');
        }
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

        try {
            foreach ($data['uploads'] as $upload) {
                $media = $request->addMedia($upload['path'])
                    ->usingName($upload['name'])
                    ->toMediaCollection('uploads', 's3_private');

                // The private disk does not throw, so a failed write would go unnoticed.
                if (! Storage::disk('s3_private')->exists($media->getPathRelativeToRoot())) {
                    throw new RuntimeException("Upload {$upload['name']} was not stored on the private disk.");
                }
            }

            $address = $data['data']['email'];
            Mail::to($address)->send(new VerificationEmail($request));
        } catch (Throwable $exception) {
            report($exception);

            $this->discard($request);
            $this->addError('save', __('contact.form.send_failed'));

            return;
        }

        $this->reset();

        $this->is_sent = true;
    }

    /**
     * Removes a request that could not be completed, so that no half saved request stays behind.
     */
    private function discard(ContactRequest $request): void
    {
        try {
            $request->forceDelete();
        } catch (Throwable $exception) {
            report($exception);
        }
    }

    /**
     * Shown while the form is loaded lazily; keeps the room the form needs so that nothing below it jumps.
     */
    public function placeholder(): string
    {
        return '<div class="min-h-[52rem]" aria-busy="true"></div>';
    }

    public function render(PagesSettings $pagesSettings)
    {
        return view('livewire.contact-form', compact('pagesSettings'));
    }
}
