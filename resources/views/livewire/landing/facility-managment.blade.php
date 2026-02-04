<div>

    <livewire:parts.landing-hero/>

{{--    <h1 class="font-logo text-7xl font-extrabold  text-white px-8">i</h1>--}}

    @if($settings->intro_layout == 'text_image')
        <livewire:parts.image-text/>
    @endif

    @if($settings->intro_layout == 'two_columns')
        <livewire:parts.two-column-text/>
    @endif

    <livewire:parts.about/>

    <livewire:parts.timeline/>

    <livewire:parts.service-v2/>

    <livewire:parts.competences/>

    <livewire:parts.references
            company="$company"
            header="{{ app()->getLocale() == 'de' ?  $settings->reference_header_de : $settings->reference_header_en}}"
            subheader="{{ app()->getLocale() == 'de' ?  $settings->reference_subheader_de : $settings->reference_subheader_en }}"
            text="{{ app()->getLocale() == 'de' ? $settings->reference_introtext_de : $settings->reference_introtext_en }}"
    />


    <livewire:parts.linke-in-wall/>

    <livewire:parts.contact/>
</div>
