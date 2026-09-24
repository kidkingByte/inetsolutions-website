@props(['title', 'subtitle' => null])
<section class="bg-gradient-to-br from-brand-700 to-brand-950 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20">
        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">{{ $title }}</h1>
        @if($subtitle)
            <p class="mt-3 max-w-2xl text-brand-100">{{ $subtitle }}</p>
        @endif
        {{ $slot }}
    </div>
</section>
