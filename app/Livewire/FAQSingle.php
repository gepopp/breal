<?php

namespace App\Livewire;

use App\Models\Question;
use App\Services\PageMeta;
use Livewire\Component;

class FAQSingle extends Component
{
    public Question $faq;

    public function mount(string $slug)
    {
        $this->faq = Question::findBySlug($slug);
    }

    public function render()
    {
        return view('livewire.f-a-q-single')
            ->title(PageMeta::compose($this->faq->question, 'be real Immobilien'))
            ->layout('components.layouts.site')
            ->layoutData([
                'canonical' => route('faq.single', $this->faq->slug),
                'description' => PageMeta::excerpt($this->faq->excerpt ?: $this->faq->answer),
            ]);
    }
}
