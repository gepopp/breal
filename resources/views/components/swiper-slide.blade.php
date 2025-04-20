@props([
    'ref'
])
<div class="swiper-slide max-w-full">
    <div>
        {{ $ref->getFirstMedia('titleimage') }}
    </div>
    <div class="flex flex-col h-full">
        <div class="mt-6">
            <h5 class="text-xl font-bold text-white h-[56px] line-clamp-2">{{ $ref->title }}</h5>
            <p class="text-white line-clamp-5 h-[140px]">{{ $ref->description }}</p>
        </div>
        <div class="my-6 text-sm font-medium text-white/50">
            @foreach($ref->tags as $tag)
                #{{ $tag }}
            @endforeach
        </div>
        <div class="flex space-x-4 mt-auto mb-12">
            <div class="w-16 shrink-0">
                <img src="{{ $ref->getFirstMediaUrl('avatar') }}"
                     class="w-16 aspect-square rounded-full shrink-0"
                     alt="{{ $ref->client_name }}"/>
            </div>
            <div class="text-white">
                <h5 class="font-semibold !text-base">
                    {{ $ref->client_name }}
                </h5>
                <p class="!text-sm !text-light">{{ $ref->testimonial }}</p>
            </div>
        </div>
    </div>
</div>