<x-section>

    <div @class([ 'max-w-screen w-full', 'md:max-w-sm lg:max-w-lg' => !is_array($preparedText)])>
        <x-headings :animate="false">
            <x-slot name="tag">{{ $settings->search_header }}</x-slot>
            {!! $settings->search_subheader !!}
        </x-headings>
        <div class="prose max-w-full">

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


    <form wire:submit class="mt-12 !bg-technik-200/25 p-4 rounded">
        <div @class(['grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4'])>
            <div>
                <flux:checkbox.group wire:model.live="filters.nutzungsart" label="{{ __('makler.search.usage_type') }}">
                    @foreach ($arten as $art )
                        @php
                            $usageLabel = match(strtoupper($art->nutzungsart)) {
                                'WOHNEN' => __('makler.usage_type.wohnen'),
                                'GEWERBE' => __('makler.usage_type.gewerbe'),
                                default => ucfirst(strtolower($art->nutzungsart))
                            };
                        @endphp
                        <flux:checkbox value="{{ $art->nutzungsart }}" label="{{ $usageLabel }}" />
                    @endforeach
                </flux:checkbox.group>
            </div>

            <div>
                <flux:checkbox.group wire:model.live="filters.typ" label="{{ __('makler.search.rent_or_buy') }}">
                    @foreach ($typen as $art )
                        @php
                            $typeLabel = match(strtolower($art->vermarktungsart)) {
                                'miete' => __('makler.marketing_type.miete'),
                                'kauf' => __('makler.marketing_type.kauf'),
                                default => ucfirst(strtolower($art->vermarktungsart))
                            };
                        @endphp
                        <flux:checkbox value="{{ $art->vermarktungsart }}" label="{{ $typeLabel }}" />
                    @endforeach
                </flux:checkbox.group>
            </div>

            <div>
                <flux:select variant="listbox" label="{{ __('makler.search.locations') }}" wire:model.live="filters.plz" size="sm" searchable multiple clear="close">
                    @foreach($plz as $p)
                        <flux:select.option value="{{ $p->plz }}">{{ $p->plz }} - {{ $p->ort }}</flux:select.option>
                    @endforeach
                </flux:select>
            </div>


            <div class="space-y-2">
                <flux:input size="sm" type="number" min="{{ $min_rooms }}" max="{{ $max_rooms }}" step="1" label="{{ __('makler.search.rooms_from') }}" placeholder="{{ $min_rooms }}" wire:model.blur="filters.rooms_min"></flux:input>
                <flux:input size="sm" type="number" min="{{ $min_rooms }}" max="{{ $max_rooms }}" step="1" label="{{ __('makler.search.rooms_to') }}" placeholder="{{ $max_rooms }}" wire:model.blur="filters.rooms_max"></flux:input>
            </div>
            <div class="space-y-2">
                <flux:input size="sm" type="number" min="{{ $min_space }}" max="{{ $max_space }}" step="1" label="{{ __('makler.search.space_from') }}" placeholder="{{ $min_space }}" wire:model.blur="filters.space_min"></flux:input>
                <flux:input size="sm" type="number" min="{{ $min_space }}" max="{{ $max_space }}" step="1" label="{{ __('makler.search.space_to') }}" placeholder="{{ $max_space }}" wire:model.blur="filters.space_max"></flux:input>
            </div>
            <div class="space-y-2">
                <flux:input size="sm" type="number" min="{{ $min_price }}" max="{{ $max_price }}" step="1" label="{{ __('makler.search.price_from') }}" placeholder="{{ $min_price }}" wire:model.blur="filters.price_min"></flux:input>
                <flux:input size="sm" type="number" min="{{ $min_price }}" max="{{ $max_price }}" step="1" label="{{ __('makler.search.price_to') }}" placeholder="{{ $max_price }}" wire:model.blur="filters.price_max"></flux:input>
            </div>
        </div>

        <div class="mt-4 mb-8 flex justify-end items-center gap-4">
            <p class="!text-xs text-gray-500 cursor-pointer" wire:click="resetFilters()">{{ __('makler.search.reset') }}</p>
            <x-button type="submit" variant="primary">{{ __('makler.search.filter_button') }}</x-button>
        </div>

    </form>

    <div class="mb-8 border-b-2 border-logo"></div>

    <flux:table :paginate="$this->realties">
        <flux:table.columns>
            <flux:table.column>{{ __('makler.table.image') }}</flux:table.column>
            <flux:table.column>{{ __('makler.table.title') }}</flux:table.column>
            <flux:table.column class="text-right" sortable :sorted="$sortBy === 'zimmer'" :direction="$sortDirection" wire:click="sort('zimmer')">{{ __('makler.table.rooms') }}</flux:table.column>
            <flux:table.column class="text-right" sortable :sorted="$sortBy === 'wohnflaeche'" :direction="$sortDirection" wire:click="sort('wohnflaeche')">{{ __('makler.table.living_area') }}</flux:table.column>
            <flux:table.column class="text-right" sortable :sorted="$sortBy === 'preis'" :direction="$sortDirection" wire:click="sort('preis')">{{ __('makler.table.price') }}</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'nutzungsart'" :direction="$sortDirection" wire:click="sort('nutzungsart')">{{ __('makler.table.usage_type') }}</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'vermarktungsart'" :direction="$sortDirection" wire:click="sort('vermarktungsart')">{{ __('makler.table.rent_buy') }}</flux:table.column>
            <flux:table.column sortable :sorted="$sortBy === 'plz'" :direction="$sortDirection" wire:click="sort('plz')">{{ __('makler.table.postal_code') }}</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse ($this->realties as $realty)
                <flux:table.row :key="$realty->id">
                    <flux:table.cell class="flex items-center gap-3 w-24">
                        <livewire:subparts.realty-thumbnail :realty="$realty" :key="'image_' . $realty->id"/>
                    </flux:table.cell>

                    <flux:table.cell class="w-1/3 whitespace-normal">
                        <a href="{{ route('makler.immobilie', $realty) }}" wire:navigate class="hover:underline">
                            {{ \Illuminate\Support\Str::words($realty->title, 8, '...') }}
                        </a>
                    </flux:table.cell>
                    <flux:table.cell class="whitespace-nowrap text-right">{{ $realty->zimmer }}</flux:table.cell>

                    <flux:table.cell class="text-right">{{ is_null($realty->wohnflaeche) ? __('makler.table.on_request') : \Illuminate\Support\Number::format( $realty->wohnflaeche, 2, null, 'de'  ) . ' m²' }}</flux:table.cell>

                    <flux:table.cell variant="strong" class="text-right">{{ is_null($realty->preis) ? __('makler.table.on_request') : Number::currency($realty->preis, 'EUR', 'de') }}</flux:table.cell>

                    <flux:table.cell>
                        @php
                            $usageLabel = match(strtoupper($realty->nutzungsart)) {
                                'WOHNEN' => __('makler.usage_type.wohnen'),
                                'GEWERBE' => __('makler.usage_type.gewerbe'),
                                default => ucfirst(strtolower($realty->nutzungsart))
                            };
                        @endphp
                        {{ $usageLabel }}
                    </flux:table.cell>
                    <flux:table.cell>
                        @php
                            $typeLabel = match(strtolower($realty->vermarktungsart)) {
                                'miete' => __('makler.marketing_type.miete'),
                                'kauf' => __('makler.marketing_type.kauf'),
                                default => ucfirst($realty->vermarktungsart)
                            };
                        @endphp
                        {{ $typeLabel }}
                    </flux:table.cell>
                    <flux:table.cell>{{ $realty->plz }} {{ $realty->ort }}</flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="7">
                        <div class="w-full aspect-video flex justify-center items-center">
                            <p>{{ __('makler.search.no_results') }}</p>
                        </div>
                    </flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>
</x-section>
