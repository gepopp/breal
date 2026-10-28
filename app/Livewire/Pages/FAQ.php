<?php

namespace App\Livewire\Pages;

use App\Enums\CompaniesEnum;
use App\Models\Question;
use App\Services\PageMeta;
use App\Settings\FaqSettings;
use App\Traits\SplitsHtmlText;
use Livewire\Component;

class FAQ extends Component
{
    use SplitsHtmlText;

    public string $company = CompaniesEnum::Hausverwaltung->name;

    public string $text = '';

    public string $locale = 'de';

    public ?array $faqs = null;

    public function mount()
    {
        $this->company = CompaniesEnum::getByRoute();

        $this->faqs = Question::all()->toArray();

        $this->locale = blank(app()->getLocale()) ? 'de' : app()->getLocale();

    }

    public function render(FaqSettings $pagesSettings)
    {
        $preparedText = $this->prepareText();

        return view('livewire.pages.f-a-q', compact('pagesSettings', 'preparedText'))
            ->title(PageMeta::title('faq'))
            ->layout('components.layouts.site')
            ->layoutData([
                'canonical' => route($this->company === CompaniesEnum::Hausverwaltung->name ? 'hausverwaltung.faq' : ($this->company === CompaniesEnum::Makler->name ? 'makler.faq' : 'technik.faq')),
                'description' => PageMeta::description('faq'),
            ]);
    }
}
