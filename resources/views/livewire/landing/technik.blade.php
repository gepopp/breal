<div>
    <livewire:parts.technik-hero/>

    @if($settings->intro_layout == 'text_image')
        <livewire:parts.technik-text-image/>
    @endif


    @if($settings->intro_layout == 'two_columns')
        <livewire:parts.technik-two-columns/>
    @endif


    <livewire:parts.technik-about/>

    <livewire:parts.contact/>
</div>