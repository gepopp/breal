@props([
    'media' => null,
    'sizes' => '100vw',
    'priority' => false,
    'describe' => false,
])
@if($media)
    @php($image = \App\Services\MediaImage::attributes($media))
    @php($mediaAlt = $describe ? $media->getCustomProperty('alt_'.app()->getLocale()) : null)
    <img src="{{ $image['src'] }}"
         @if($image['srcset']) srcset="{{ $image['srcset'] }}" sizes="{{ $sizes }}" @endif
         @if($image['width']) width="{{ $image['width'] }}" height="{{ $image['height'] }}" @endif
         loading="{{ $priority ? 'eager' : 'lazy' }}"
         @if($priority) fetchpriority="high" @endif
         decoding="async"
         @if($mediaAlt) alt="{{ $mediaAlt }}" @endif
         {{ $mediaAlt ? $attributes->except('alt') : $attributes }}/>
@endif
