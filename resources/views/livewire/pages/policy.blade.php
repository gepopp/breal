<x-section>
    <div>
        <div>
            <div>
                <div>
                    <x-headings level="1">
                        <x-slot name="tag">secure</x-slot>
                        {{ __('pages.policy.heading') }}
                    </x-headings>
                </div>
            </div>
            <div>
                <div class="prose">
                    {!! $pagesSettings->dpgr_text !!}

                    @include('legal.provider-inventory-'.(app()->getLocale() === 'de' ? 'de' : 'en'))
                </div>
            </div>
        </div>
    </div>
</x-section>
