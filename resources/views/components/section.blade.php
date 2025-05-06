<section {{ $attributes->merge([ 'class' => 'py-12 md:py-24 flex justify-center' ]) }}>
        <div class="lg:max-w-4xl xl:max-w-6xl w-full px-4">
            {{ $slot }}
        </div>
</section>