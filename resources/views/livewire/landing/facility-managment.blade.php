<div class="px-4 md:px-0">
    <livewire:parts.landing-hero
            company="$company"
            header="{{ $settings->hero_header }}"
            subheader="{{ $settings->hero_subheader }}"
            intro="{{ $settings->hero_introtext }}"
            image="{{ $settings->hero_image }}"
            alt="{{ $settings->hero_image_alt }}"
    />

    <livewire:parts.image-text
            text="{{ $settings->text }}"
            image="{{ $settings->text_image }}"
            alt="{{ $settings->text_image_alt }}"
    />


    <livewire:parts.about
            company="$company"
            header="{{ $settings->about_header }}"
            subheader="{{ $settings->about_subheader }}"
            text="{{ $settings->about_text }}"
            image="{{ $settings->about_image }}"
            alt="{{ $settings->about_image_alt }}"
    />


</div>
