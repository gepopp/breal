<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class JobVacancy extends Component
{
    public \App\Models\JobVacancy $jobVacancy;


    public function mount(\App\Models\JobVacancy $JobVacancy){

        $this->jobVacancy = $JobVacancy;
    }


    public function render()
    {
        return view('livewire.pages.job-vacancy');
    }
}
