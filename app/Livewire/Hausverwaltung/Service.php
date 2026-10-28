<?php

namespace App\Livewire\Hausverwaltung;

use App\Models\Service as ServiceModel;
use App\Settings\PagesSettings;
use App\Traits\SplitsHtmlText;
use Livewire\Attributes\Url;
use Livewire\Component;

class Service extends Component
{
    use SplitsHtmlText;

    public $text;

    #[Url]
    public ?string $tab = '';

    public function mount(PagesSettings $pagesSettings): void
    {
        $this->text = $pagesSettings->services_introtext;
    }

    public function render(PagesSettings $pagesSettings)
    {
        $services = ServiceModel::all();
        $selected = $services->firstWhere('slug', $this->tab) ?? $services->first();
        $preparedText = $this->prepareText();

        return view('livewire.hausverwaltung.service', [
            'pagesSettings' => $pagesSettings,
            'preparedText' => $preparedText,
            'menu' => $services->map(fn (ServiceModel $service): array => [
                'id' => $service->id,
                'slug' => $service->slug,
                'name' => $service->translatedFor('name'),
                'link' => $service->link,
            ])->all(),
            'selected' => $selected ? $this->present($selected) : null,
        ])
            ->title(__('pages.service.title'))
            ->layout('components.layouts.site')
            ->layoutData([
                'canonical' => $selected && $selected->isNot($services->first()) ? $selected->link : route('hausverwaltung.service'),
                'description' => __('pages.service.description'),
            ]);
    }

    /**
     * @return array{slug: string, name: string, description: string, form: ?string, list: array<int, array<string, mixed>>, links: array<int, array<string, mixed>>}
     */
    protected function present(ServiceModel $service): array
    {
        return [
            'slug' => $service->slug,
            'name' => $service->translatedFor('name'),
            'description' => $service->translatedFor('description'),
            'form' => $service->form,
            'list' => $service->list ?? [],
            'links' => collect($service->links ?? [])->where('lang', app()->getLocale())->values()->all(),
        ];
    }
}
