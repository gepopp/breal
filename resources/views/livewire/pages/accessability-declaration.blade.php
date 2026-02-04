<x-section>
    <div>
        <div>
            <div>
                <div>
                    <x-headings level="1">
                        <x-slot name="tag">{{ __('legal.accessibility_tag') }}</x-slot>
                        {{ __('legal.accessibility_title') }}
                    </x-headings>
                </div>
            </div>
            <div>
                <div class="prose">
                    {!! $pagesSettings->accessability_text !!}
                </div>
            </div>
        </div>
    </div>
</x-section>