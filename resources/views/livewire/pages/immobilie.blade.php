<x-section>
    <div class="flex">
        <div class="w-2/3">
            <h1 class="text-2xl font-bold">{{ $realty->title }}</h1>
            <livewire:subparts.realty-slider :realty="$realty" />

            <div class="prose">
                {!! $realty->beschreibung !!}
            </div>
        </div>
    </div>
</x-section>
