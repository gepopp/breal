<?php

namespace App\Livewire\Pages;

use App\Models\Question;
use App\Enums\CompaniesEnum;
use App\Settings\FaqSettings;
use App\Settings\PagesSettings;
use App\Traits\SplitsHtmlText;
use Livewire\Component;

class FAQ extends Component
{
    use SplitsHtmlText;

    public string $company = CompaniesEnum::Hausverwaltung->name;

    public string $text = '';

    public ?array $faqs = null;

    public function mount(FaqSettings $pagesSettings)
    {
        $this->company = CompaniesEnum::getByRoute();

        $this->faqs = Question::all()->toArray();
    }

    public function render(FaqSettings $pagesSettings)
    {
        $preparedText = $this->prepareText();

        return view('livewire.pages.f-a-q', compact('pagesSettings', 'preparedText'))
            ->title(__('pages.faq.title'))
            ->layout('components.layouts.site')
            ->layoutData([
                'canonical' => route($this->company === CompaniesEnum::Hausverwaltung->name ? 'hausverwaltung.faq' : ($this->company === CompaniesEnum::Makler->name ? 'makler.faq' : 'technik.faq')),
                'description' => __('pages.faq.description'),
            ]);
    }
}
