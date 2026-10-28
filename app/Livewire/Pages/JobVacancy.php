<?php

namespace App\Livewire\Pages;

use App\Services\PageMeta;
use Livewire\Component;

class JobVacancy extends Component
{
    public \App\Models\JobVacancy $jobVacancy;

    public function mount(\App\Models\JobVacancy $JobVacancy)
    {

        $this->jobVacancy = $JobVacancy;

        if ($this->jobVacancy->from > now() || $this->jobVacancy->to < now()) {
            abort(403);
        }

    }

    public function render()
    {
        return view('livewire.pages.job-vacancy')
            ->title(PageMeta::compose($this->jobVacancy->title, __('meta.job.suffix')))
            ->layout('components.layouts.site')
            ->layoutData([
                'canonical' => route('stellenanzeige', $this->jobVacancy),
                'description' => PageMeta::excerpt($this->jobVacancy->description),
            ]);
    }
}
