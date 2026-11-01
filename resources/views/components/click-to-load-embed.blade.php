@props(['code', 'modal'])

@php
    // Only the markup of the embed is kept: the provider script is not needed to play the video and must not load on its own.
    $markup = preg_replace('#<script\b.*?</script>#is', '', (string) $code);
@endphp

{{--
    Third-party embeds (e.g. Vimeo) are not part of the page until the visitor opens the modal that shows them,
    and they are removed again when it is closed.
--}}
<div x-data="{ loaded: false, markup: @js($markup) }"
     x-on:modal-show.document="if ($event.detail.name === '{{ $modal }}') loaded = true"
     x-on:modal-close.document="if ($event.detail.name === '{{ $modal }}') loaded = false">
    <template x-if="loaded">
        <div x-html="markup"></div>
    </template>
</div>
