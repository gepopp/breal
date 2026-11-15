@props([
    'media' => null,
    'sizes' => '100vw',
    'priority' => false,
    'describe' => false,
    'deferred' => false,
])
@if($media)
    @php($image = \App\Services\MediaImage::attributes($media))
    @php($mediaAlt = $describe ? $media->getCustomProperty('alt_'.app()->getLocale()) : null)
    @if($priority)
        @php(app(\App\Services\PreloadedImages::class)->add($image['src'], $image['srcset'], $image['srcset'] ? $sizes : null))
    @endif
    {{-- deferred: src/srcset are restored after the window load event, so off-screen slider images do not compete with the LCP image --}}
    <img @if($deferred) data-deferred-src="{{ $image['src'] }}" @else src="{{ $image['src'] }}" @endif
         @if($image['srcset']) @if($deferred) data-deferred-srcset="{{ $image['srcset'] }}" @else srcset="{{ $image['srcset'] }}" @endif sizes="{{ $sizes }}" @endif
         @if($image['width']) width="{{ $image['width'] }}" height="{{ $image['height'] }}" @endif
         loading="{{ $priority ? 'eager' : 'lazy' }}"
         @if($priority) fetchpriority="high" @endif
         decoding="async"
         @if($mediaAlt) alt="{{ $mediaAlt }}" @endif
         {{ $mediaAlt ? $attributes->except('alt') : $attributes }}/>
@endif
