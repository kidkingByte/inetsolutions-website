@php
    $allOk = $statuses->every(fn ($s) => $s->isOperational());
    $badge = [
        'operational' => ['Operational', 'bg-emerald-100 text-emerald-700 border-emerald-200', 'bg-emerald-400'],
        'degraded' => ['Degraded', 'bg-amber-100 text-amber-700 border-amber-200', 'bg-amber-400'],
        'outage' => ['Outage', 'bg-red-100 text-red-700 border-red-200', 'bg-red-400'],
        'maintenance' => ['Maintenance', 'bg-sky-100 text-sky-700 border-sky-200', 'bg-sky-400'],
    ];
@endphp
<x-site-layout>
    <x-slot name="title">Network Status — {{ site('company_name') }}</x-slot>

    <x-site.page-banner eyebrow="Status" title="Network Status" subtitle="Current status of INET services, updated by our network operations team." />

    <section class="section">
        <div class="container-x max-w-4xl">
            <div class="reveal flex items-center gap-4 rounded-3xl border p-6 sm:p-8 {{ $allOk ? 'border-emerald-200 bg-emerald-50' : 'border-amber-200 bg-amber-50' }}">
                <span class="relative flex h-4 w-4">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full {{ $allOk ? 'bg-emerald-400' : 'bg-amber-400' }} opacity-60"></span>
                    <span class="relative inline-flex h-4 w-4 rounded-full {{ $allOk ? 'bg-emerald-400' : 'bg-amber-400' }}"></span>
                </span>
                <div>
                    <p class="text-xl font-bold text-ink">{{ $allOk ? 'All Systems Operational' : 'Some services are experiencing issues' }}</p>
                    <p class="text-sm text-slate-500">Last updated {{ optional($statuses->max('updated_at'))->diffForHumans() ?? '—' }}</p>
                </div>
            </div>

            <ul class="reveal mt-6 divide-y divide-slate-200 overflow-hidden rounded-3xl border border-slate-200 bg-white">
                @forelse($statuses as $s)
                    @php [$label, $classes, $dot] = $badge[$s->status] ?? [ucfirst($s->status), 'bg-slate-100 text-slate-600 border-slate-200', 'bg-slate-400']; @endphp
                    <li class="flex items-center justify-between gap-4 px-6 py-5">
                        <div class="flex items-center gap-4">
                            <span class="h-2.5 w-2.5 rounded-full {{ $dot }}"></span>
                            <div>
                                <p class="font-semibold text-ink">{{ $s->service }}</p>
                                @if($s->message)<p class="text-sm text-slate-500">{{ $s->message }}</p>@endif
                            </div>
                        </div>
                        <span class="shrink-0 rounded-full border px-3 py-1 text-xs font-semibold {{ $classes }}">{{ $label }}</span>
                    </li>
                @empty
                    <li class="px-6 py-5 text-slate-500">No status information available.</li>
                @endforelse
            </ul>

            <p class="mt-8 text-center text-sm text-slate-500">Experiencing a problem? <a href="{{ route('support.report') }}" class="font-semibold text-brand-600 hover:text-brand-700">Report it here</a>.</p>
        </div>
    </section>
</x-site-layout>
