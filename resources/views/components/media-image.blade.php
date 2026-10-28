@props([
    'media' => null,
    'sizes' => '100vw',
    'priority' => false,
])
@if($media)
    @php($image = \App\Services\MediaImage::attributes($media))
    <img src="{{ $image['src'] }}"
         @if($image['srcset']) srcset="{{ $image['srcset'] }}" sizes="{{ $sizes }}" @endif
         @if($image['width']) width="{{ $image['width'] }}" height="{{ $image['height'] }}" @endif
         loading="{{ $priority ? 'eager' : 'lazy' }}"
         @if($priority) fetchpriority="high" @endif
         decoding="async"
         {{ $attributes }}/>
@endif
