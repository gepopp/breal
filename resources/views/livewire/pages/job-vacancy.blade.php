<x-section>

    <div class="my-12">

        <x-headings>
            <x-slot name="tag">{{ $jobVacancy->job_title }}</x-slot>
            {!! $jobVacancy->title !!}
        </x-headings>


        <div class="flex mt-12">
            <div class="w-3/4" data-aos="fade-up">
                {!! $jobVacancy->description !!}
            </div>
            <div class="w-1/4 ml-6 pl-6 border-l-2 border-logo">
                <ul class="text-right">
                    <li data-aos="fade-up" data-aos-delay="50" class="text-xl font-medium">Stellenbezeichnung:</li>
                    <li data-aos="fade-up" data-aos-delay="100" class="text-right">{{ $jobVacancy->job_title }}</li>
                    <li data-aos="fade-up" data-aos-delay="150" class="text-xl font-medium mt-4">Anstellungsart:</li>
                    <li data-aos="fade-up" data-aos-delay="200" class="text-right">{{ $jobVacancy->contract_type }}</li>
                    <li data-aos="fade-up" data-aos-delay="250" class="text-xl font-medium mt-4">Bezahlung:</li>
                    <li data-aos="fade-up" data-aos-delay="300" class="text-right">
                        {{ \Illuminate\Support\Number::currency( $jobVacancy->salary, 'EUR') }}
                         <span class="text-xs"> / {{ $jobVacancy->unit_text }}</span></li>
                    <li data-aos="fade-up" data-aos-delay="350" class="text-xl font-medium mt-4">Bewerbunben an:</li>
                    <li data-aos="fade-up" data-aos-delay="400" class="text-right">
                        <a href="mailto:{{ $jobVacancy->email }}">
                            {{ $jobVacancy->email }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</x-section>
