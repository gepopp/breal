<?php

namespace App\Livewire\Pages;

use App\Services\PageMeta;
use App\Services\StructuredData;
use Livewire\Component;
use Symfony\Component\HttpKernel\Exception\GoneHttpException;

class JobVacancy extends Component
{
    public \App\Models\JobVacancy $jobVacancy;

    public function mount(\App\Models\JobVacancy $JobVacancy)
    {

        $this->jobVacancy = $JobVacancy;

        if ($this->jobVacancy->from > now()) {
            abort(404);
        }

        // Expired for good: 410 tells job portals and search engines to drop the ad.
        if ($this->jobVacancy->to < now()) {
            throw new GoneHttpException;
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
                'schema' => [StructuredData::jobPosting($this->jobVacancy)],
            ]);
    }
}
