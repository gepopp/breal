<x-section>
    <div @class(['md:max-w-sm lg:max-w-lg'])>
        <x-headings>
            <x-slot name="tag">{{ $settings->contact_header }}</x-slot>
            {!! $settings->contact_subheader !!}
        </x-headings>
        <div data-aos="fade" data-aos-delay="600" class="prose">
            {!! $settings->contact_introtext !!}
        </div>
    </div>

    <div class="py-12">
        <livewire:contact-form/>
    </div>
</x-section>