<?php

namespace App\Livewire\Pages;

use App\Models\Realty;
use App\Settings\PagesSettings;
use App\Traits\SplitsHtmlText;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Immobiliensuche extends Component
{
    use WithPagination;
    use SplitsHtmlText;

    public $sortBy = 'date';
    public $sortDirection = 'desc';

    public array $arten = [];

    public array $typen = [];

    public array $plz = [];

    public int $min_rooms = 0;
    public int $max_rooms = 0;
    public int $min_price = 0;
    public int $max_price = 0;

    public int $min_space = 0;
    public int $max_space = 0;

    #[Url]
    public array $filters = [
        'nutzungsart' => [],
        'typ'         => [],
        'rooms_min'   => null,
        'rooms_max'   => null,
        'price_min'   => null,
        'price_max'   => null,
        'space_min'   => null,
        'space_max'   => null,
        'plz'         => [],
    ];


    public function rules()
    {
        return [
            'filters'             => 'array',
            'filters.nutzungsart' => 'nullable|array',
            'filters.typ'         => 'nullable|array',
            'filters.rooms_min'   => 'nullable|integer|min:' . $this->min_rooms,
            'filters.rooms_max'   => 'nullable|integer|min:' . $this->min_rooms . '|max:' . $this->max_rooms . '|gt:filters.rooms_min',
            'filters.price_min'   => 'nullable|integer|min:' . $this->min_price . '|max:' . $this->max_price . '|gt:filters.price_min',
            'filters.price_max'   => 'nullable|integer|min:' . $this->min_price . '|max:' . $this->max_price . '|gt:filters.price_min',
            'filters.space_min'   => 'nullable|integer|min:' . $this->min_space . '|max:' . $this->max_space . '|gt:filters.space_min',
            'filters.space_max'   => 'nullable|integer|min:' . $this->min_space . '|max:' . $this->max_space . '|gt:filters.space_min',
            'filters.plz'         => 'nullable|array|min:1'
        ];
    }


    public function mount()
    {
        $this->arten =  DB::table('realties')->select('nutzungsart')->distinct()->get()->toArray();
        $this->typen =  DB::table('realties')->select('vermarktungsart')->distinct()->get()->toArray();
        $this->plz = DB::table('realties')->select('plz', 'ort')->distinct()->get()->toArray();
        $this->min_rooms = DB::table('realties')->min('zimmer');
        $this->max_rooms = DB::table('realties')->max('zimmer');
        $this->min_price = (int)DB::table('realties')->min('preis');
        $this->max_price = (int)DB::table('realties')->max('preis');
        $this->min_space = (int)DB::table('realties')->min('wohnflaeche');
        $this->max_space = (int)DB::table('realties')->max('wohnflaeche');


        foreach ($this->arten as $value) {
            $this->filters['nutzungsart'][] = $value->nutzungsart;
        }

        foreach ($this->typen as $value) {
            $this->filters['typ'][] = $value->vermarktungsart;
        }
    }


    public function resetFilters()
    {
        $this->resetPage();
        $this->reset('filters');;
    }

    public function sort($column)
    {
        $this->resetPage();
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }


    public function updatedFilters()
    {
        $this->resetPage();
    }


    #[Computed]
    public function realties()
    {
        $query = Realty::query()
            ->tap(fn($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query);

        if ($this->filters['nutzungsart']) {
            $query->whereIn('nutzungsart', $this->filters['nutzungsart']);
        }

        if ($this->filters['typ']) {
            $query->whereIn('vermarktungsart', $this->filters['typ']);
        }

        if ($this->filters['rooms_min']) {
            $query->where('zimmer', '>=', $this->filters['rooms_min']);
        }

        if ($this->filters['rooms_max']) {
            $query->where('zimmer', '<=', $this->filters['rooms_max']);
        }

        if ($this->filters['price_min']) {
            $query->where('preis', '>=', $this->filters['price_min']);
        }

        if ($this->filters['price_max']) {
            $query->where('preis', '<=', $this->filters['price_max']);
        }

        if ($this->filters['space_min']) {
            $query->where('wohnflaeche', '>=', $this->filters['space_min']);
        }

        if ($this->filters['space_max']) {
            $query->where('wohnflaeche', '<=', $this->filters['space_max']);
        }

        if(count($this->filters['plz']) > 0){
            $query->whereIn('plz', $this->filters['plz']);
        }

        return $query->paginate(25);
    }


    public function render(PagesSettings $settings)
    {
        $preparedText = $this->prepareText($settings->search_introtext);
        return view('livewire.pages.immobiliensuche', compact('settings', 'preparedText'));
    }
}
