@props(['title', 'subtitle' => null, 'eyebrow' => null])
<section class="relative overflow-hidden border-b border-slate-100 bg-brand-light pb-16 pt-36 sm:pb-20 sm:pt-44">
    <div class="pointer-events-none absolute inset-0 bg-wash"></div>
    {{-- Logo motifs: signal arcs + the blue slash, both drawing themselves in --}}
    <x-site.signal-arcs :animated="false" id="banner-arcs" class="pointer-events-none absolute -right-10 top-16 hidden h-80 w-80 opacity-[0.14] md:block lg:right-10" />
    <div class="slash-draw pointer-events-none absolute -top-10 right-[22%] hidden h-[140%] w-[3px] rotate-[28deg] rounded-full bg-gradient-to-b from-transparent via-brand-600/30 to-transparent md:block"></div>

    <div class="container-x relative">
        @if($eyebrow)
            <p class="eyebrow intro" style="--i:0">{{ $eyebrow }}</p>
        @endif
        <h1 class="intro mt-5 max-w-4xl text-4xl font-extrabold leading-[1.08] sm:text-6xl" style="--i:1">{{ $title }}</h1>
        @if($subtitle)
            <p class="lead intro mt-6 max-w-2xl" style="--i:2">{{ $subtitle }}</p>
        @endif
        <div class="intro" style="--i:3">{{ $slot }}</div>
    </div>
</section>
