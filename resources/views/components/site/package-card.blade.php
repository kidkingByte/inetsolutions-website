@props(['package'])
@php
    $price = (float) ($package->price ?? 0);
    $featured = $package->is_featured;
    $icon = ['home' => 'home', 'business' => 'building', 'enterprise' => 'server'][$package->category] ?? 'wifi';
@endphp
<div {{ $attributes->merge(['class' => 'reveal relative flex flex-col rounded-3xl p-7 transition duration-300 hover:-translate-y-1 ' . ($featured ? 'gradient-border shadow-[0_30px_80px_-30px_rgba(70,60,165,0.35)]' : 'border border-slate-200 bg-white hover:border-brand-200 hover:shadow-xl hover:shadow-brand-900/5')]) }}>
    @if($featured)
        <span class="absolute -top-3 left-7 inline-flex items-center gap-1 rounded-full bg-signal px-3 py-1 text-xs font-semibold text-white">
            <x-site.icon name="sparkles" class="h-3.5 w-3.5" /> Most popular
        </span>
    @endif

    <div class="flex items-center justify-between">
        <span class="icon-tile"><x-site.icon :name="$icon" class="h-6 w-6" /></span>
        <span class="rounded-full border border-slate-200 px-3 py-1 text-xs font-medium capitalize text-slate-500">{{ $package->category }}</span>
    </div>

    <h3 class="mt-6 text-xl font-bold">{{ $package->name }}</h3>
    @if($package->speed)
        <p class="mt-1 text-sm font-medium text-brand-600">Up to {{ $package->speed }}</p>
    @endif

    <div class="mt-6 border-t border-slate-200 pt-6">
        @if($price > 0)
            <div class="flex items-baseline gap-2">
                <span class="text-sm font-semibold text-slate-500">TZS</span>
                <span class="text-4xl font-extrabold text-ink">{{ number_format($price) }}</span>
            </div>
            <p class="mt-1 text-sm text-slate-500">per {{ $package->validity ?: 'month' }}</p>
        @else
            <p class="text-2xl font-extrabold text-ink">{{ $package->category === 'enterprise' ? 'Custom pricing' : 'Price on request' }}</p>
            <p class="mt-1 text-sm text-slate-500">{{ $package->category === 'enterprise' ? 'Talk to our sales team' : 'Confirmed by our sales team' }}</p>
        @endif
    </div>

    @if($package->description)
        <p class="mt-5 text-sm leading-relaxed text-slate-500">{{ $package->description }}</p>
    @endif

    <ul class="mt-6 flex-1 space-y-3 text-sm">
        @foreach($package->feature_list as $feature)
            <li class="flex items-start gap-3 text-slate-600">
                <span class="mt-0.5 inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-brand-50 text-brand-600"><x-site.icon name="check" class="h-3 w-3" /></span>
                {{ $feature }}
            </li>
        @endforeach
    </ul>

    @if($package->recommended_users)
        <p class="mt-6 flex items-center gap-2 text-xs text-slate-500"><x-site.icon name="users" class="h-4 w-4" /> Recommended for up to {{ $package->recommended_users }} users</p>
    @endif

    <a href="{{ route('get-connected', ['package' => $package->name, 'service' => $package->category]) }}"
       class="{{ $featured ? 'btn-primary' : 'btn-secondary' }} mt-7 w-full">
        {{ $package->category === 'enterprise' ? 'Contact Sales' : 'Subscribe Now' }}
    </a>
</div>
