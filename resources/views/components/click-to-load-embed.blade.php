@props(['code', 'modal'])

@php
    // Only the markup of the embed is kept: the provider script is not needed to play the video and must not load on its own.
    $markup = preg_replace('#<script\b.*?</script>#is', '', (string) $code);
@endphp

{{--
    Third-party embeds (e.g. Vimeo) are not part of the page until the visitor opens the modal that shows them,
    and they are removed again when it is closed. Closing with Escape, a click outside or the close button only
    does not reliably send Flux's modal-close event, so the dialog's native "toggle" event removes the embed too.
--}}
<div x-data="{ loaded: false, markup: @js($markup) }"
     x-init="$el.closest('dialog')?.addEventListener('toggle', (event) => { if (event.newState === 'closed') loaded = false })"
     x-on:modal-show.document="if ($event.detail.name === '{{ $modal }}') loaded = true"
     x-on:modal-close.document="if ($event.detail.name === '{{ $modal }}') loaded = false">
    <template x-if="loaded">
        <div x-html="markup"></div>
    </template>
</div>
