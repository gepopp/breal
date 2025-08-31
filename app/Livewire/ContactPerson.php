<?php

namespace App\Livewire;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class ContactPerson extends Component
{
    public \App\Models\Contactperson $contactperson;


    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('sort', function (Builder $builder) {
            $builder->orderByDesc('sort');
        });
    }

    public function render()
    {
        return view('livewire.contact-person');
    }
}
