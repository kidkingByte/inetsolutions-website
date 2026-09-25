@props(['title', 'subtitle' => null, 'eyebrow' => null, 'center' => true])
<div {{ $attributes->merge(['class' => 'reveal max-w-3xl ' . ($center ? 'mx-auto text-center' : '')]) }}>
    @if($eyebrow)
        <p class="eyebrow {{ $center ? 'justify-center' : '' }}">{{ $eyebrow }}</p>
    @endif
    <h2 class="h-section mt-4">{{ $title }}</h2>
    @if($subtitle)
        <p class="lead mt-5">{{ $subtitle }}</p>
    @endif
</div>
