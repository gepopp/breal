<?php

namespace App\Livewire\Pages;

use App\Enums\CompaniesEnum;
use App\Models\Question;
use App\Services\PageMeta;
use App\Services\StructuredData;
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

        $this->locale = blank(app()->getLocale()) ? 'de' : app()->getLocale();

        $this->faqs = Question::all()
            ->map(fn (Question $question): array => [
                'question' => $this->translated($question, 'question'),
                'answer' => $this->translated($question, 'answer'),
                'link' => $question->link,
            ])
            ->all();

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
                'schema' => [StructuredData::faqPage($this->faqs)],
            ]);
    }

    /**
     * The text in the page language; German until an English text exists.
     */
    private function translated(Question $question, string $attribute): string
    {
        return $question->getTranslation($attribute, $this->locale, false) ?: $question->getTranslation($attribute, 'de', false);
    }
}
