<x-section>
    <section @class([
        "mb-12",
        "text-logo-950" => \Illuminate\Support\Facades\Route::is('hausverwaltung.*'),
        "text-technik-950" => \Illuminate\Support\Facades\Route::is('technik.*'),
        "text-makler-950" => \Illuminate\Support\Facades\Route::is('makler.*'),
    ])>
        <div class="max-w-md w-full">
            <div @class([
        "tagged-heading mb-4",
        "text-logo-500" => !Route::is('technik.*', 'makler.*'),
        "text-technik-500" => Route::is('technik.*'),
        "text-makler-500" => Route::is('makler.*'),
])>
                <div @class([
        "flex items-center font-logo text-3xl md:text-4xl lg:text-5xl xl:text-7xl font-extrabold rounded tracking-wide lowercase",
        "before:content-['be'] before:h-[32px] before:mb-[6px] before:md:h-[36px] before:lg:h-[42px] before:xl:h-[62px] before:align-baseline before:rounded before:px-2 before:tracking-wide dark:before:bg-white before:bg-logo dark:before:text-logo before:mr-2",
        "text-logo-500" => !Route::is('technik.*', 'makler.*'),
        "text-technik-500" => Route::is('technik.*'),
        "text-makler-500" => Route::is('makler.*'),
        "before:text-white before:bg-logo"
        ])>
                    <span class="font-logo dark:text-white">{{ $pagesSettings->services_header }}</span>
                </div>
                <h1 class="text-xl font-bold dark:text-white">{{ $pagesSettings->services_subheader }}</h1>
            </div>
        </div>
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
    </section>


    <section class="mt-12 flex flex-col md:flex-row space-x-8" x-data="siteServiceFilter({{ Js::from(array_map(fn (array $item): array => ['id' => $item['id'], 'name' => $item['name']], $menu)) }})">
        <div class="relative min-h-[300px] md:min-h-screen w-full md:w-84 shrink-0">
            <div class="inset-0 bg-white flex flex-col">
                @if(count($menu) >= config('company.service_filter_min_items'))
                    <flux:input x-model="searchterm" class="border-logo" icon:trailing="funnel" placeholder="{{ __('service.filter_placeholder', ['count' => count($menu)]) }}"/>
                @endif
                <div @class(['relative', 'mt-4 pt-4' => count($menu) >= config('company.service_filter_min_items')])>
                    <ul class="overflow-y-auto overflow-x-hidden scrollbar scrollbar-thin  !list-none space-y-4">
                        @foreach($menu as $item)
                            <li class="border border-logo rounded-lg !ml-0 relative group cursor-pointer" x-show="visibleIds.includes({{ $item['id'] }})" wire:key="service-menu-{{ $item['id'] }}">
                                <a href="{{ $item['link'] }}" wire:click.prevent="$set('tab', '{{ $item['slug'] }}')" class="block p-2">
                                    <p class="!font-bold !mb-0 !dark:text-logo !text-logo !text-sm">{{ $item['name'] }}</p>
                                    <div @class([
                                        "absolute inset-0 rounded-lg bg-logo p-2 text-white",
                                        "translate-x-[98%] -scale-y-75 group-hover:scale-y-100 group-hover:translate-x-0 transition-all duration-500 ease-in" => ($selected['slug'] ?? null) !== $item['slug'],
                                    ])>
                                        <p class="!font-bold !mb-0 !text-white !text-sm">{{ $item['name'] }}</p>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="mt-4 pt-4 border-t border-logo">
                    <x-button href="https://realonline.bereal-immobilien.at/">{{ __('navigation.customer_login') }}</x-button>
                </div>
            </div>
        </div>


        <div class="w-full md:w-auto mt-10 md:mt-0">
            @if($selected)
                <div wire:key="service-content-{{ $selected['slug'] }}">
                    <div class="md:p-4 pt-0 prose grow">{!! $selected['description'] !!}</div>

                    @if($selected['form'] === 'form')
                        <div class="px-3">
                            <livewire:contact-form :sidebar="false" wire:key="form_without_address"/>
                        </div>
                    @endif

                    @if($selected['form'] === 'address')
                        <div class="px-3">
                            <livewire:contact-form :sidebar="false" :address="true" wire:key="form_with_address"/>
                        </div>
                    @endif

                    @foreach($selected['list'] as $list)
                        <div class="pt-8 mt-8 border-t-4 border-logo/50">
                            <ul @class(["font-medium !list-outside space-y-2", "!list-disc !ml-8" => ($list['icon'] ?? 'none') == 'none'])>
                                @foreach($list['list'] ?? [] as $listItem)
                                    <li>
                                        <div @class(["flex items-center" => ($list['icon'] ?? 'none') !== 'none'])>
                                            <div>
                                                @if(in_array($list['icon'] ?? 'none', ['stern', 'rufzeichen', 'arrow'], true))
                                                    <div class="flex items-center justify-center w-7 rounded-full text-white aspect-square bg-logo/40 mr-2">
                                                        <svg class="size-6" data-slot="icon" fill="none" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                            @if($list['icon'] === 'stern')
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"></path>
                                                            @elseif($list['icon'] === 'rufzeichen')
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"></path>
                                                            @else
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="m12.75 15 3-3m0 0-3-3m3 3h-7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"></path>
                                                            @endif
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <p class="!font-medium">{{ $listItem }}</p>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach

                    @if(count($selected['links']) > 0)
                        <div class="pt-8 mt-8 border-t-4 border-logo/50">
                            @foreach($selected['links'] as $download)
                                <a target="_blank" href="{{ $download['url'] }}" class="flex items-center space-x-4 group">
                                    <div>
                                        <svg class="size-8" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="group-hover:underline">{{ $download['name'] }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </section>
</x-section>
@script
<script>
    Alpine.data('siteServiceFilter', (items) => ({
        items: items,
        visibleIds: items.map(item => item.id),
        searchterm: '',
        fuse: null,
        init() {
            this.fuse = new window.Fuse(this.items, {
                keys: ['name'],
                threshold: 0.4
            });

            this.$watch('searchterm', () => {
                this.filterServices();
            })
        },
        filterServices() {
            if (this.searchterm.length < 3) {
                this.visibleIds = this.items.map(item => item.id);
            } else {
                this.visibleIds = this.fuse.search(this.searchterm).map(result => result.item.id);
            }
        }
    }))
</script>
@endscript
