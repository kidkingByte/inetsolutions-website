@php
    $allOk = $statuses->every(fn ($s) => $s->isOperational());
    $badge = ['operational' => ['Operational', 'bg-green-100 text-green-700'], 'degraded' => ['Degraded', 'bg-amber-100 text-amber-700'], 'outage' => ['Outage', 'bg-red-100 text-red-700'], 'maintenance' => ['Maintenance', 'bg-blue-100 text-blue-700']];
@endphp
<x-site-layout>
    <x-slot name="title">Network Status — {{ site('company_name') }}</x-slot>
    <x-site.page-banner title="Network Status" subtitle="Real-time status of INET services." />

    <section class="py-20 bg-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-2xl px-6 py-5 text-center font-semibold {{ $allOk ? 'bg-green-50 text-green-800' : 'bg-amber-50 text-amber-800' }}">
                {{ $allOk ? 'All Systems Operational' : 'Some services are experiencing issues' }}
            </div>
            <ul class="mt-6 divide-y divide-slate-200 rounded-2xl border border-slate-200">
                @forelse($statuses as $s)
                    <li class="flex items-center justify-between p-5">
                        <div>
                            <p class="font-semibold text-brand-950">{{ $s->service }}</p>
                            @if($s->message)<p class="text-sm text-slate-500">{{ $s->message }}</p>@endif
                        </div>
                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $badge[$s->status][1] ?? 'bg-slate-100 text-slate-700' }}">{{ $badge[$s->status][0] ?? ucfirst($s->status) }}</span>
                    </li>
                @empty
                    <li class="p-5 text-slate-500">No status information available.</li>
                @endforelse
            </ul>
            <p class="mt-6 text-center text-sm text-slate-500">Experiencing a problem? <a href="{{ route('support.report') }}" class="text-brand-700 font-semibold">Report it here</a>.</p>
        </div>
    </section>
</x-site-layout>
