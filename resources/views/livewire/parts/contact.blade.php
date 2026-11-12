<x-section>
    <div @class(['md:max-w-sm lg:max-w-lg'])>
        <x-headings level="2">
            <x-slot name="tag">{{ $settings->contact_header }}</x-slot>
            {!! $settings->contact_subheader !!}
        </x-headings>
        <div data-aos="fade" data-aos-delay="600" class="prose">
            {!! $introtext !!}
        </div>
    </div>

    <div class="py-12">
        <livewire:contact-form lazy="on-load" :company="\App\Enums\CompaniesEnum::getByRoute()"/>
    </div>
</x-section>