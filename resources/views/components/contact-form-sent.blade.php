<div role="status" aria-live="polite" x-bind:inert="!is_sent" x-bind:aria-hidden="(!is_sent).toString()"
     class="bg-logo text-white p-4 flex flex-col justify-center items-center delay-[1s]">
    <div class="max-w-xs text-center">
        <h5 class="text-2xl font-bold">{{ __('Vielen Dank!') }}</h5>
        <p class="!text-sm font-thin">
            {{ __('Damit wir Ihre Anfrage bearbeiten können, bestätigen Sie bitte Ihre E-Mail-Adresse.') }}
        </p>
        <p class="!text-sm font-thin">
            {{ __('Dazu haben wir Ihnen eine E-Mail mit einem Bestätigungslink gesendet.') }}
        </p>
    </div>
</div>