<?php

namespace App\Livewire\Sites;

use App\Enums\CompaniesEnum;
use App\Services\CompanyInfo;
use App\Services\PageMeta;
use Livewire\Component;

class Imprint extends Component
{
    public function render()
    {
        $companies = array_map(
            fn (CompaniesEnum $company): array => CompanyInfo::section($company->name),
            CompaniesEnum::cases()
        );

        return view('livewire.sites.imprint', compact('companies'))
            ->title(PageMeta::title('impressum'))
            ->layout('components.layouts.site')
            ->layoutData([
                'canonical' => route('impressum'),
                'description' => PageMeta::description('impressum'),
            ]);
    }
}
