{{--
    Filament parses the stored sidebar state without error handling: an invalid value stops its sidebar script and
    leaves the admin unusable. Remove such a value before that script runs; Filament then rebuilds it.
--}}
<script>
    try {
        const key = 'filament:sidebar:collapsed_groups'
        const stored = localStorage.getItem(key)

        if (stored !== null) {
            let parsed

            try {
                parsed = JSON.parse(stored)
            } catch (error) {
                parsed = undefined
            }

            // `null` is handled by Filament itself.
            if (parsed !== null && ! (Array.isArray(parsed) && parsed.every((group) => typeof group === 'string'))) {
                localStorage.removeItem(key)
            }
        }
    } catch (error) {
        // Storage can be unavailable; nothing to repair then.
    }
</script>
