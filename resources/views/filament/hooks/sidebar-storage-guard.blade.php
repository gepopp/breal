{{--
    The sidebar parses its stored state without error handling: an invalid value stops its script and leaves the admin
    unusable. Replace such a value (including `null`) with an empty list before that script runs. A missing entry is
    left alone, the sidebar creates it with its defaults.
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

            // The sidebar needs a list; `null` and anything invalid becomes an empty list (no collapsed groups).
            if (! (Array.isArray(parsed) && parsed.every((group) => typeof group === 'string'))) {
                localStorage.setItem(key, '[]')
            }
        } catch (error) {
            // Storage can be unavailable; nothing to repair then.
        }
    })
</script>
