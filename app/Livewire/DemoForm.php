<?php

namespace App\Livewire;

use App\Mail\VerificationEmail;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Rule;
use Livewire\Component;

class DemoForm extends Component
{

    #[Rule(rule: ['string', 'required', 'max:255'], as: 'Name')]
    public string $name = 'Gerhard';

    #[Rule(rule: ['string', 'required', 'email:rfc,dns'], as: 'E-Mail-Adresse')]
    public string $email = 'gerhard@weloveinteraction.com';

    public bool $is_sent = false;


    public function save()
    {
        $data = $this->validate();

        Mail::to($data['email'])->send( new VerificationEmail() );

        $this->reset();

        $this->is_sent = true;
    }


    public function render()
    {
        return view('livewire.demo-form');
    }
}
