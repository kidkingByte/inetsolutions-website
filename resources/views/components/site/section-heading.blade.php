@props(['title', 'subtitle' => null, 'center' => true])
<div {{ $attributes->merge(['class' => ($center ? 'text-center mx-auto' : '') . ' max-w-2xl']) }}>
    <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-brand-950">{{ $title }}</h2>
    @if($subtitle)
        <p class="mt-3 text-slate-600">{{ $subtitle }}</p>
    @endif
</div>
