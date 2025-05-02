<x-section>

    <div @class([ 'max-w-screen w-full', 'md:max-w-sm lg:max-w-lg' => !is_array($preparedText)])>
        <x-headings>
            <x-slot name="tag">{{ $settings->search_header }}</x-slot>
            {!! $settings->search_subheader !!}
        </x-headings>
        <div data-aos="fade" data-aos-delay="600" class="prose max-w-full">

            @if(!is_array($preparedText))
                {!! html_entity_decode( $preparedText ) !!}
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 md:gap-12 w-full">
                    <div>{!! $preparedText['firstHalf'] !!}</div>
                    <div>{!! $preparedText['secondHalf'] !!}</div>
                </div>
            @endif
        </div>
    </div>


    <form wire:submit="filter" class="mt-12 !bg-technik-200/25 p-4 rounded">
        <div @class(['grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-8'])>
            <div>
                <flux:radio.group wire:model.live="filters.nutzungsart" label="Nutzungsart">
                    @foreach ($arten as $art )
                        <flux:radio value="{{ $art->nutzungsart }}" label="{{ ucfirst($art->nutzungsart) }}"/>
                    @endforeach
                </flux:radio.group>
            </div>

            <div>
                <flux:radio.group wire:model.live="filters.typ" label="Miete oder Kauf">
                    @foreach ($typen as $art )
                        <flux:radio value="{{ $art->vermarktungsart }}" label="{{ ucfirst($art->vermarktungsart) }}"/>
                    @endforeach
                </flux:radio.group>
            </div>
            <div class="space-y-2">
                <flux:input size="sm" type="number" min="{{ $min_rooms }}" max="{{ $max_rooms }}" step="1" label="Zimmer von" placeholder="{{ $min_rooms }}" wire:model.blur="filters.rooms_min"></flux:input>
                <flux:input size="sm" type="number" min="{{ $min_rooms }}" max="{{ $max_rooms }}" step="1" label="Zimmer bis" placeholder="{{ $max_rooms }}" wire:model.blur="filters.rooms_max"></flux:input>
            </div>
            <div class="space-y-2">
                <flux:input size="sm" type="number" min="{{ $min_space }}" max="{{ $max_space }}" step="1" label="Fläche von" placeholder="{{ $min_space }}" wire:model.blur="filters.space_min"></flux:input>
                <flux:input size="sm" type="number" min="{{ $min_space }}" max="{{ $max_space }}" step="1" label="Fläche bis" placeholder="{{ $max_space }}" wire:model.blur="filters.space_max"></flux:input>
            </div>
            <div class="space-y-2">
                <flux:input size="sm" type="number" min="{{ $min_price }}" max="{{ $max_price }}" step="1" label="Preis von" placeholder="{{ $min_price }}" wire:model.blur="filters.price_min"></flux:input>
                <flux:input size="sm" type="number" min="{{ $min_price }}" max="{{ $max_price }}" step="1" label="Preis bis" placeholder="{{ $max_price }}" wire:model.blur="filters.price_max"></flux:input>
            </div>
        </div>

        <div class="mt-4 mb-8 flex justify-end items-center gap-4">
            <p class="!text-xs text-gray-500 cursor-pointer" wire:click="resetFilters()">zurücksetzen</p>
            <x-button type="submit" variant="primary">Filtern</x-button>
        </div>

    </form>

    <div class="mb-8 border-b-2 border-logo"></div>

    <flux:table :paginate="$this->realties">
        <flux:table.columns>
            <flux:table.column>Bild</flux:table.column>
            <flux:table.column>Titel</flux:table.column>
            <flux:table.column class="text-right" sortable :sorted="$sortBy === 'zimmer'" :direction="$sortDirection" wire:click="sort('zimmer')">Zimmer</flux:table.column>
            <flux:table.column class="text-right" sortable :sorted="$sortBy === 'wohnflaeche'" :direction="$sortDirection" wire:click="sort('wohnflaeche')">Wohnflaeche</flux:table.column>
            <flux:table.column class="text-right" sortable :sorted="$sortBy === 'preis'" :direction="$sortDirection" wire:click="sort('preis')">Preis</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'nutzungsart'" :direction="$sortDirection" wire:click="sort('nutzungsart')">Nutzungsart</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'vermarktungsart'" :direction="$sortDirection" wire:click="sort('vermarktungsart')">Miete / Kauf</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse ($this->realties as $realty)
                <flux:table.row :key="$realty->id">
                    <flux:table.cell class="flex items-center gap-3 w-24">
                        <livewire:subparts.realty-thumbnail :realty="$realty" :key="'image_' . $realty->id" />
                    </flux:table.cell>

                    <flux:table.cell class="w-1/3 whitespace-normal">{{ \Illuminate\Support\Str::words($realty->title, 8, '...') }}</flux:table.cell>
                    <flux:table.cell class="whitespace-nowrap text-right">{{ $realty->zimmer }}</flux:table.cell>

                    <flux:table.cell class="text-right">{{ is_null($realty->wohnflaeche) ? 'Auf Anfrage' : \Illuminate\Support\Number::format( $realty->wohnflaeche, 2, null, 'de'  ) }} m²</flux:table.cell>

                    <flux:table.cell variant="strong" class="text-right">{{ is_null($realty->preis) ? 'Auf Anfrage' : Number::currency($realty->preis, 'EUR', 'de') }}</flux:table.cell>

                    <flux:table.cell>{{ ucfirst(strtolower($realty->nutzungsart)) }}</flux:table.cell>
                    <flux:table.cell>{{ ucfirst($realty->vermarktungsart) }}</flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="7">
                        <div class="w-full aspect-video flex justify-center items-center">
                            <p>Keine Immobilien gefunden, bitte passen Sie ihre Suchkriterien an.</p>
                        </div>
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>
</x-section>
