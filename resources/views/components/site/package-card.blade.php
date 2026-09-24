@props(['package'])
@php
    $price = (float) ($package->price ?? 0);
    $featured = $package->is_featured;
@endphp
<div {{ $attributes->merge(['class' => 'relative flex flex-col rounded-2xl border ' . ($featured ? 'border-brand-600 ring-2 ring-brand-600/20 shadow-lg' : 'border-slate-200 shadow-sm') . ' bg-white p-6']) }}>
    @if($featured)
        <span class="absolute -top-3 left-6 rounded-full bg-brand-600 px-3 py-1 text-xs font-semibold text-white">Popular</span>
    @endif

    <h3 class="text-lg font-bold text-brand-950">{{ $package->name }}</h3>
    <p class="mt-1 text-sm text-slate-500 capitalize">{{ $package->category }} internet</p>

    <div class="mt-4">
        @if($price > 0)
            <div class="flex items-end gap-1">
                <span class="text-3xl font-extrabold text-brand-700">{{ number_format($price) }}</span>
                <span class="mb-1 text-sm text-slate-500">TZS / {{ $package->validity ?: 'mo' }}</span>
            </div>
        @else
            <div class="text-2xl font-extrabold text-brand-700">Price on request</div>
        @endif
        @if($package->speed)
            <p class="mt-1 text-sm font-medium text-slate-700">Up to {{ $package->speed }}</p>
        @endif
    </div>

    @if($package->description)
        <p class="mt-4 text-sm text-slate-600">{{ $package->description }}</p>
    @endif

    <ul class="mt-4 space-y-2 text-sm text-slate-700">
        @foreach($package->feature_list as $feature)
            <li class="flex items-start gap-2">
                <svg class="h-4 w-4 mt-0.5 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-7.5 7.5a1 1 0 01-1.4 0L3.3 9.7a1 1 0 011.4-1.4l3.1 3.1 6.8-6.8a1 1 0 011.4 0z" clip-rule="evenodd"/></svg>
                <span>{{ $feature }}</span>
            </li>
        @endforeach
    </ul>

    @if($package->recommended_users)
        <p class="mt-4 text-xs text-slate-500">Recommended for up to {{ $package->recommended_users }} users</p>
    @endif

    <div class="mt-6 pt-2">
        <a href="{{ route('get-connected', ['package' => $package->name, 'service' => $package->category]) }}"
           class="block text-center w-full rounded-lg px-4 py-2.5 text-sm font-semibold {{ $featured ? 'bg-brand-600 text-white hover:bg-brand-700' : 'bg-brand-50 text-brand-700 hover:bg-brand-100' }}">
            Subscribe Now
        </a>
    </div>
</div>
