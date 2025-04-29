<div>
{{--    {!! dd($settings) !!}--}}

        <livewire:parts.landing-hero
                company="$company"
                header="{{ $settings->hero_header }}"
                subheader="{{ $settings->hero_subheader }}"
                intro="{{ $settings->hero_introtext }}"
                :image="$settings->hero_image"
                alt="{{ $settings->hero_image_alt }}"
        />


    @if($settings->intro_layout == 'text_image')
        <livewire:parts.image-text
                text="{{ $settings->text }}"
                :image="$settings->text_image"
                alt="{{ $settings->text_image_alt }}"
        />
    @endif


    @if($settings->intro_layout == 'two_columns')
        <livewire:parts.two-column-text
                company="$company"
                columnLeft="{{ $settings->hero_text_column_one }}"
                columnRight="{{ $settings->hero_text_column_two }}"
        />
    @endif


    <livewire:parts.about
            header="{{ $settings->about_header }}"
            subheader="{{ $settings->about_subheader }}"
            text="{{ $settings->about_text }}"
            image="{{ $settings->about_image }}"
            alt="{{ $settings->about_image_alt }}"
            video="{{ $settings->about_video_embed_code }}"
    />

    <livewire:parts.timeline
            company="$company"
            header="{{ $settings->timeline_header }}"
            subheader="{{ $settings->timeline_subheader }}"
            text="{{ $settings->timeline_intro }}"
    />

    <livewire:parts.service-v2
            company="$company"
            header="{{ $settings->service_heading }}"
            subheader="{{ $settings->service_subheading }}"
            text="{{ $settings->service_introtext }}"
    />

    <livewire:parts.competences
            company="$company"
            header="{{ $settings->competence_header }}"
            subheader="{{ $settings->competence_subheader }}"
            text="{{ $settings->competence_introtext }}"
    />

{{--    <livewire:parts.references--}}
{{--            company="$company"--}}
{{--            header="{{ $settings->reference_header }}"--}}
{{--            subheader="{{ $settings->reference_subheader }}"--}}
{{--            text="{{ $settings->reference_introtext }}"--}}
{{--    />--}}


    <livewire:parts.linke-in-wall/>

    <livewire:parts.contact
            company="$company"
            header="{{ $settings->contact_header }}"
            subheader="{{ $settings->contact_subheader }}"
            text="{{ $settings->contact_introtext }}"
    />
</div>
