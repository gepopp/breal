<x-section>
    <div class="flex space-x-8">
        <div class="w-2/3">
            <h1 class="text-2xl font-bold">{{ $realty->title }}</h1>
            <livewire:subparts.realty-slider :realty="$realty" />

            <div class="prose">
                {!! $realty->beschreibung !!}
            </div>
        </div>
        <div class="w-1/3">
            <div class="prose">
                {!! $realty->beschreibung !!}
            </div>
        </div>
    </div>
</x-section>
