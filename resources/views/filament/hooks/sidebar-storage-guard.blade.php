{{--
    The sidebar parses its stored state without error handling: an invalid value stops its script and leaves the admin
    unusable. Remove such a value before that script runs; the sidebar then rebuilds it.
    Two keys are in use: Filament's own, and `collapsedGroups` of the older copy of the sidebar view in
    resources/views/vendor/filament-panels/livewire/sidebar.blade.php.
--}}
<script>
    ['filament:sidebar:collapsed_groups', 'collapsedGroups'].forEach((key) => {
        try {
            const stored = localStorage.getItem(key)

            if (stored === null) {
                return
            }

            let parsed

            try {
                parsed = JSON.parse(stored)
            } catch (error) {
                parsed = undefined
            }

            // `null` is handled by the sidebar itself.
            if (parsed !== null && ! (Array.isArray(parsed) && parsed.every((group) => typeof group === 'string'))) {
                localStorage.removeItem(key)
            }
        } catch (error) {
            // Storage can be unavailable; nothing to repair then.
        }
    })
</script>
