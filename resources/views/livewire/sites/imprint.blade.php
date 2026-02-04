<x-section>
    <div>
        <div>
            <div>
                <div>
                    <x-headings level="1">
                        <x-slot name="tag">{{ __('legal.imprint_tag') }}</x-slot>
                        {{ __('legal.imprint_title') }}
                    </x-headings>
                </div>
            </div>
            <div>
                <div class="prose">
                    {!! $pagesSettings->imprint_text !!}
                </div>
            </div>
        </div>
    </div>
</x-section>