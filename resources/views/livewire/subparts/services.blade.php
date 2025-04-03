<div class="my-12 relative">
    <div class="grid grid-cols-1 md:grid-cols-2 md:gap-6 lg:gap-12 gap-y-8">
        @foreach($services as $service)
            <div data-aos="fade-up" class="border-2 border-logo rounded-2xl p-4 lg:p-6 relative overflow-hidden group">
                <h5 class="font-bold text-xl">{{ $service->name }}</h5>
                <ul class="text-logo-950 opacity-75 font-light">
                    @foreach($service->points as $point)
                        <li class="flex items-center space-x-2">
                            <svg class="size-4" data-slot="icon" fill="none" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                            </svg>
                            <span>{{ $point }}</span>
                        </li>
                    @endforeach
                </ul>

                <div class="absolute inset-0 rounded-lg bg-logo p-4 lg:p-6 text-white translate-x-[98%] -scale-y-75 group-hover:scale-y-100 group-hover:translate-x-0 transition-all duration-500 ease-in">
                    <h5 class="font-bold text-xl">{{ $service->name }}</h5>
                    <ul class="text-white font-light">
                        @foreach($service->points as $point)
                            <li class="flex items-center space-x-2">
                                <svg class="size-4" data-slot="icon" fill="none" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                                </svg>
                                <span>{{ $point }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
    </div>
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
</div>
