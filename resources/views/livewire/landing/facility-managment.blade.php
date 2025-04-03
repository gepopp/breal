<div class="px-4">
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
</div>
