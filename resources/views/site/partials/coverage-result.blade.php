@php
    // Rendered on a full page load and returned as `html` for in-place (AJAX) results.
    $styles = [
        'available' => ['border-emerald-200 bg-emerald-50', 'bg-emerald-100 text-emerald-700', 'check', 'Great news'],
        'partial' => ['border-sky-200 bg-sky-50', 'bg-sky-100 text-sky-700', 'map-pin', 'Almost there'],
        'coming_soon' => ['border-amber-200 bg-amber-50', 'bg-amber-100 text-amber-700', 'clock', 'Coming soon'],
        'not_available' => ['border-slate-200 bg-slate-50', 'bg-slate-100 text-slate-600', 'alert', 'Not yet available'],
    ];
    [$box, $badge, $icon, $label] = $styles[$result['status']] ?? $styles['not_available'];
    $place = collect([$input['ward'] ?? null, $input['district'] ?? null, $input['region'] ?? null])->filter()->implode(', ');
    $connectParams = array_filter([
        'service' => $result['area']->service_type ?? ($input['service_required'] ?? null),
        'region' => $input['region'] ?? null,
        'district' => $input['district'] ?? null,
        'ward' => $input['ward'] ?? null,
    ]);
@endphp
<div class="rounded-3xl border p-6 sm:p-8 {{ $box }}" role="status" tabindex="-1">
    <div class="flex items-start gap-4">
        <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl {{ $badge }}"><x-site.icon :name="$icon" class="h-6 w-6" /></span>
        <div class="min-w-0 flex-1">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">{{ $label }}</p>
            <p class="mt-1 text-xl font-bold text-ink">{{ $result['message'] }}</p>
            @if($place)
                <p class="mt-2 flex items-center gap-2 text-sm text-slate-500"><x-site.icon name="map-pin" class="h-4 w-4" /> {{ $place }}</p>
            @endif
            @if($result['status'] === 'available' && $result['installation_available'])
                <p class="mt-2 flex items-center gap-2 text-sm text-emerald-700"><x-site.icon name="wrench" class="h-4 w-4" /> Installation available in this area</p>
            @endif

            @if(in_array($result['status'], ['available', 'partial'], true))
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('get-connected', $connectParams) }}" class="btn-primary">Get Connected <x-site.icon name="arrow-right" class="h-4 w-4" /></a>
                    <a href="{{ route('packages') }}" class="btn-secondary">View packages</a>
                </div>
            @else
                <form method="POST" action="{{ route('coverage.notify') }}" class="mt-6 space-y-3">
                    @csrf
                    <p class="text-sm text-slate-600">{{ $result['status'] === 'coming_soon' ? 'Leave your details and we will notify you when INET is live in your area.' : 'Request coverage — leave your details and our team will contact you.' }}</p>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <label class="sr-only" for="notify-name">Full name</label>
                        <input id="notify-name" name="full_name" required placeholder="Full name" value="{{ $input['full_name'] ?? '' }}" class="field">
                        <label class="sr-only" for="notify-phone">Phone</label>
                        <input id="notify-phone" name="phone" type="tel" required placeholder="Phone number" value="{{ $input['phone'] ?? '' }}" class="field">
                    </div>
                    <input type="hidden" name="region" value="{{ $input['region'] ?? '' }}">
                    <input type="hidden" name="district" value="{{ $input['district'] ?? '' }}">
                    <input type="hidden" name="ward" value="{{ $input['ward'] ?? '' }}">
                    <button class="btn-primary">{{ $result['status'] === 'coming_soon' ? 'Notify Me' : 'Request Coverage' }}</button>
                </form>
            @endif
        </div>
    </div>
</div>
