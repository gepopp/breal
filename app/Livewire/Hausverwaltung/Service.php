<?php

namespace App\Livewire\Hausverwaltung;

use App\Models\Service as ServiceModel;
use App\Services\PageMeta;
use App\Settings\ServicesSettings;
use App\Traits\SplitsHtmlText;
use Livewire\Attributes\Url;
use Livewire\Component;

class Service extends Component
{
    use SplitsHtmlText;

    public $text;

    #[Url]
    public ?string $tab = '';

    public function mount(ServicesSettings $pagesSettings): void
    {
        $this->text = $pagesSettings->services_introtext;
    }

    public function render(ServicesSettings $pagesSettings)
    {
        $services = ServiceModel::all();
        $selected = $services->firstWhere('slug', $this->tab) ?? $services->first();
        $preparedText = $this->prepareText();

        // The default tab is the page itself, the other tabs are indexed on their own and need their own title.
        $ownMeta = $selected && $selected->isNot($services->first());

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
            ->title($ownMeta ? PageMeta::compose($selected->translatedFor('name')) : PageMeta::title('service'))
            ->layout('components.layouts.site')
            ->layoutData([
                'canonical' => $selected && $selected->isNot($services->first()) ? $selected->link : route('hausverwaltung.service'),
                'description' => $ownMeta && ($excerpt = PageMeta::excerpt($selected->translatedFor('description'))) !== '' ? $excerpt : PageMeta::description('service'),
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
            'links' => $this->documentLinks($service),
        ];
    }

    /**
     * Documents in the page language; where only a German document exists, the English page offers that one, labelled as German.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function documentLinks(ServiceModel $service): array
    {
        $links = collect($service->links ?? []);
        $localized = $links->where('lang', app()->getLocale())->values();

        if ($localized->isNotEmpty() || app()->getLocale() === 'de') {
            return $localized->all();
        }

        return $links->where('lang', 'de')
            ->map(fn (array $link): array => ['name' => $link['name'].' ('.__('service.german_document').')'] + $link)
            ->values()
            ->all();
    }
}
