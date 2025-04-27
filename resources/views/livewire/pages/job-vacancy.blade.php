<x-section>

    <div class="my-12">

        <x-headings>
            <x-slot name="tag">{{ $jobVacancy->job_title }}</x-slot>
            {!! $jobVacancy->title !!}
        </x-headings>


        <div class="flex flex-col md:flex-row mt-12">
            <div class="w-full md:w-3/4" data-aos="fade-up">
                {!! $jobVacancy->description !!}
            </div>
            <div class="w-full mt-12 md:mt-0 md:w-1/4 md:ml-6 md:pl-6 md:border-l-2 md:border-logo">
                <ul class="md:text-right">
                    <li data-aos="fade-up" data-aos-delay="50" class="text-xl font-medium">Stellenbezeichnung:</li>
                    <li data-aos="fade-up" data-aos-delay="100">{{ $jobVacancy->job_title }}</li>
                    <li data-aos="fade-up" data-aos-delay="150" class="text-xl font-medium mt-4">Anstellungsart:</li>
                    <li data-aos="fade-up" data-aos-delay="200">{{ $jobVacancy->contract_type }}</li>
                    <li data-aos="fade-up" data-aos-delay="250" class="text-xl font-medium mt-4">Bezahlung:</li>
                    <li data-aos="fade-up" data-aos-delay="300">
                        {{ \Illuminate\Support\Number::currency( $jobVacancy->salary, 'EUR') }}
                         <span class="text-xs"> / {{ $jobVacancy->unit_text }}</span></li>
                    <li data-aos="fade-up" data-aos-delay="350" class="text-xl font-medium mt-4">Bewerbunben an:</li>
                    <li data-aos="fade-up" data-aos-delay="400">
                        <a href="mailto:{{ $jobVacancy->email }}">
                            {{ $jobVacancy->email }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="mt-12 flex justify-center">
            <x-button href="mailto:{{ $jobVacancy->email }}" data-aos="fade-up" data-aos-delay="500">Jetzt bewerben</x-button>
        </div>
    </div>
</x-section>
