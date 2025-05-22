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
            header="{{ $settings->reference_header }}"
            subheader="{{ $settings->reference_subheader }}"
            text="{{ $settings->reference_introtext }}"
    />


    <livewire:parts.linke-in-wall/>

    <livewire:parts.contact/>
</div>
