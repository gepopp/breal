<div class="mt-8 mb-16 w-full aspect-video bg-gray-200 flex justify-center items-center">
    @if($is_cookie_accepted)
        <div x-data="Map"></div>
        <div class="w-full aspect-video" x-ref="map"></div>
    @else
        <div class="max-w-[60%] flex flex-col items-center justify-center space-y-3">
            <p class="text-sm text-center">
                Zum anzeigen von Google Maps akzeptieren Sie bitte opitionale Cookies.
            </p>
            <x-button wire:click="acceptCookie">akzeptieren</x-button>
        </div>

    @endif
</div>
@script
<script>
    Alpine.data('Map', () => ({
        init() {
            window.loader.load().then(async () => {
                const {Map} = await google.maps.importLibrary("maps");

                let map = new Map(this.$refs.map, {
                    center: {lat: {{ $geo['@attributes']['breitengrad'] }}, lng: {{ $geo['@attributes']['laengengrad'] }}},
                    zoom: 14,
                });


                let marker = new google.maps.Marker({
                    position: {lat: {{ $geo['@attributes']['breitengrad'] }}, lng: {{ $geo['@attributes']['laengengrad'] }}},
                    map,
                });
            });
        }
    }))
</script>
@endscript
