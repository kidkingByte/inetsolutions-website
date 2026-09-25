@props(['animated' => true, 'draw' => true, 'id' => 'arcs'])
{{-- The four signal arcs from the INET logo, drawn as a scalable brand motif.
     draw: arcs draw in from the smallest outwards; animated: then keep pulsing. --}}
<svg viewBox="0 0 200 200" fill="none" aria-hidden="true" {{ $attributes->class(['signal-arcs' => $animated, 'arcs-draw' => $draw]) }}>
    <defs>
        <linearGradient id="{{ $id }}-g" x1="20" y1="20" x2="190" y2="170" gradientUnits="userSpaceOnUse">
            <stop offset="0" stop-color="#463CA5" />
            <stop offset=".55" stop-color="#9B2C7E" />
            <stop offset="1" stop-color="#E2202C" />
        </linearGradient>
    </defs>
    @foreach([45, 80, 115, 150] as $r)
        {{-- quarter arc around (190,190), from the left edge up to the top; pathLength lets CSS draw it --}}
        <path class="arc" pathLength="1" d="M{{ 190 - $r }} 190 A{{ $r }} {{ $r }} 0 0 1 190 {{ 190 - $r }}" stroke="url(#{{ $id }}-g)" stroke-width="17" stroke-linecap="round" />
    @endforeach
</svg>
