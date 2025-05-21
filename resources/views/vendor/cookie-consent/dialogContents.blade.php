<div class="js-cookie-consent cookie-consent fixed bottom-0 right-0 mb-12 mr-12  rounded-xl max-w-md pb-2 z-50 bg-white shadow-xl border-t border-logo-500">
    <div class="max-w-7xl mx-auto px-6">
        <div class="p-4 md:p-2 rounded-lg">
            <div class="flex items-center justify-between flex-wrap space-y-3">
                <div class="max-w-full flex-1 items-center md:w-0 md:inline">
                    <p class="md:ml-3 text-black cookie-consent__message">
                        {!! trans('cookie-consent::texts.message') !!}
                    </p>
                </div>
                <div class="mt-6 flex-shrink-0 w-full sm:mt-0 sm:w-auto">
                    <x-button class="js-cookie-consent-agree cookie-consent__agree">
                        akzeptieren
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</div>
