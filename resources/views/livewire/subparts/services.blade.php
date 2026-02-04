<div class="my-12 relative">
    <div class="grid grid-cols-1 md:grid-cols-2 md:gap-6 lg:gap-12 gap-y-8">
        @foreach($services as $service)
            <div data-aos="fade-up" class="border-2 border-logo rounded-2xl p-4 lg:p-6 relative overflow-hidden group">
                <h5 class="font-bold text-xl">{{ $service->name }}</h5>

                <div class="absolute inset-0 rounded-lg bg-logo p-4 lg:p-6 text-white translate-x-[98%] -scale-y-75 group-hover:scale-y-100 group-hover:translate-x-0 transition-all duration-500 ease-in">
                    <h5 class="font-bold text-xl">{{ $service->name }}</h5>
                </div>
            </div>
        @endforeach
    </div>
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
</div>
