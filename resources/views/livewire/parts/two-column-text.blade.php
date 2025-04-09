<x-section>
    <div class="grid grid-cols-1 md:grid-cols-2 md:gap-12 max-w-4xl mx-auto">
        <div data-aos="fade" class="prose">
            {!! html_entity_decode($columnLeft) !!}
        </div>
        <div data-aos="fade" data-aos-delay="600" class="prose">
            {!! html_entity_decode($columnRight) !!}
        </div>
    </div>
</x-section>
