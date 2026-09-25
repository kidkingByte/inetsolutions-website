@extends('layouts.admin')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')
    @php
        $maxType = max(1, $byType->max() ?? 0);
        $pipeline = ['new', 'contacted', 'qualified', 'installation_scheduled', 'installed', 'converted'];
        $pipelineMax = max(1, collect($pipeline)->map(fn ($s) => $byStatus[$s] ?? 0)->max());
        $statusColors = ['new' => 'bg-accent-50 text-accent-700 ring-accent-200', 'contacted' => 'bg-brand-50 text-brand-700 ring-brand-200', 'qualified' => 'bg-violet-50 text-violet-700 ring-violet-200', 'installation_scheduled' => 'bg-amber-50 text-amber-700 ring-amber-200', 'installed' => 'bg-emerald-50 text-emerald-700 ring-emerald-200', 'converted' => 'bg-emerald-50 text-emerald-700 ring-emerald-200'];
    @endphp

    <p class="text-slate-500">Welcome back, <span class="font-semibold text-ink">{{ auth()->user()->name }}</span> — here's what needs your attention today.</p>

    {{-- KPIs --}}
    @if($kpis)
        <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($kpis as $kpi)
                <a href="{{ $kpi['href'] }}" class="card card-hover !p-5">
                    <div class="flex items-start justify-between">
                        <p class="text-sm font-medium text-slate-500">{{ $kpi['label'] }}</p>
                        <span class="icon-tile h-10 w-10"><x-site.icon :name="$kpi['icon']" class="h-5 w-5" /></span>
                    </div>
                    <p class="mt-2 text-3xl font-extrabold tabular-nums text-ink">{{ number_format($kpi['value']) }}</p>
                    <p class="mt-1 text-xs text-slate-500">{{ $kpi['hint'] }}</p>
                </a>
            @endforeach
        </div>
    @endif

    @if($showEnquiries)
        <div class="mt-6 grid gap-6 xl:grid-cols-3">
            {{-- Needs attention --}}
            <section class="card !p-0 xl:col-span-2">
                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                    <h2 class="flex items-center gap-2 font-bold text-ink">
                        <span class="flex h-2.5 w-2.5 rounded-full {{ $overdueCount ? 'bg-accent-600' : 'bg-emerald-500' }}"></span>
                        Needs attention
                        @if($overdueCount)<span class="text-sm font-normal text-slate-500">— {{ $overdueCount }} new {{ Str::plural('enquiry', $overdueCount) }} waiting over 24 hours</span>@endif
                    </h2>
                    <a href="{{ route('admin.enquiries.index', ['status' => 'new']) }}" class="link-arrow">All new</a>
                </div>
                @forelse ($attention as $e)
                    <a href="{{ route('admin.enquiries.show', $e) }}" class="flex items-center gap-4 border-b border-slate-100 px-5 py-3 last:border-0 hover:bg-slate-50">
                        <span class="icon-tile h-10 w-10 shrink-0 {{ $e->isSupport() ? '!bg-amber-50 !text-amber-600 !ring-amber-100' : '' }}"><x-site.icon :name="$e->isSupport() ? 'support' : 'users'" class="h-5 w-5" /></span>
                        <span class="min-w-0 flex-1">
                            <span class="block truncate font-semibold text-ink">{{ $e->full_name ?: 'Unnamed visitor' }}</span>
                            <span class="block truncate text-xs text-slate-500">{{ $e->type_label }} · {{ collect([$e->ward, $e->district, $e->region])->filter()->implode(', ') ?: 'No location' }}</span>
                        </span>
                        <span class="shrink-0 text-right text-xs font-semibold text-accent-700">{{ $e->created_at->diffForHumans(short: true) }}</span>
                    </a>
                @empty
                    <div class="flex items-center gap-3 px-5 py-10 text-sm text-slate-500"><x-site.icon name="check" class="h-5 w-5 text-emerald-500" /> All caught up — every new enquiry has been handled within a day.</div>
                @endforelse
            </section>

            {{-- Assigned to me --}}
            <section class="card !p-0">
                <div class="border-b border-slate-100 px-5 py-4">
                    <h2 class="font-bold text-ink">Assigned to me</h2>
                </div>
                @forelse ($mine as $e)
                    <a href="{{ route('admin.enquiries.show', $e) }}" class="block border-b border-slate-100 px-5 py-3 last:border-0 hover:bg-slate-50">
                        <span class="flex items-center justify-between gap-3">
                            <span class="truncate font-semibold text-ink">{{ $e->full_name ?: 'Unnamed visitor' }}</span>
                            <span class="shrink-0 rounded-full px-2 py-0.5 text-xs font-semibold ring-1 {{ $statusColors[$e->status] ?? 'bg-slate-50 text-slate-600 ring-slate-200' }}">{{ $e->status_label }}</span>
                        </span>
                        <span class="text-xs text-slate-500">{{ $e->type_label }} · updated {{ $e->updated_at->diffForHumans() }}</span>
                    </a>
                @empty
                    <p class="px-5 py-10 text-sm text-slate-500">Nothing assigned to you right now.</p>
                @endforelse
                <div class="border-t border-slate-100 px-5 py-3"><a href="{{ route('admin.enquiries.index', ['assigned' => 'me', 'status' => 'open']) }}" class="link-arrow">My open enquiries</a></div>
            </section>
        </div>

        <div class="mt-6 grid gap-6 xl:grid-cols-3">
            {{-- Pipeline --}}
            <section class="card xl:col-span-2">
                <h2 class="font-bold text-ink">Pipeline</h2>
                <p class="text-sm text-slate-500">Where every enquiry stands, from first contact to a connected customer.</p>
                <div class="mt-6 space-y-3">
                    @foreach ($pipeline as $status)
                        @php $count = $byStatus[$status] ?? 0; @endphp
                        <a href="{{ route('admin.enquiries.index', ['status' => $status]) }}" class="grid grid-cols-[9rem_1fr_3rem] items-center gap-3 rounded-lg text-sm hover:bg-slate-50">
                            <span class="text-slate-600">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                            <span class="h-2.5 overflow-hidden rounded-full bg-slate-100"><span class="block h-full rounded-full bg-signal" style="width: {{ $count ? max(3, $count / $pipelineMax * 100) : 0 }}%"></span></span>
                            <span class="text-right font-semibold tabular-nums text-ink">{{ $count }}</span>
                        </a>
                    @endforeach
                </div>
            </section>

            {{-- By type (bars relative to the largest type) --}}
            <section class="card">
                <h2 class="font-bold text-ink">Enquiries by type</h2>
                <div class="mt-5 space-y-3">
                    @forelse ($byType as $type => $total)
                        <a href="{{ route('admin.enquiries.index', ['type' => $type]) }}" class="block rounded-lg hover:bg-slate-50">
                            <span class="flex justify-between text-sm"><span class="text-slate-600">{{ \App\Models\Enquiry::TYPES[$type] ?? $type }}</span><span class="font-semibold tabular-nums text-ink">{{ $total }}</span></span>
                            <span class="mt-1 block h-2 overflow-hidden rounded-full bg-slate-100"><span class="block h-full rounded-full bg-brand-600" style="width: {{ $total / $maxType * 100 }}%"></span></span>
                        </a>
                    @empty
                        <p class="text-sm text-slate-500">No enquiries yet.</p>
                    @endforelse
                </div>
            </section>
        </div>

        {{-- Recent --}}
        <section class="card mt-6 !p-0">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <h2 class="font-bold text-ink">Latest enquiries</h2>
                <a href="{{ route('admin.enquiries.index') }}" class="link-arrow">View all</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[40rem] text-sm">
                    <thead class="bg-slate-50 text-left text-xs uppercase tracking-wider text-slate-500">
                        <tr><th class="px-5 py-3 font-semibold">Name</th><th class="px-5 py-3 font-semibold">Type</th><th class="px-5 py-3 font-semibold">Status</th><th class="px-5 py-3 font-semibold">Assigned</th><th class="px-5 py-3 font-semibold">Received</th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($recent as $e)
                            <tr class="hover:bg-slate-50">
                                <td class="px-5 py-3"><a href="{{ route('admin.enquiries.show', $e) }}" class="font-semibold text-brand-700 hover:underline">{{ $e->full_name ?: 'Unnamed visitor' }}</a></td>
                                <td class="px-5 py-3 text-slate-600">{{ $e->type_label }}</td>
                                <td class="px-5 py-3"><span class="rounded-full px-2 py-0.5 text-xs font-semibold ring-1 {{ $statusColors[$e->status] ?? 'bg-slate-50 text-slate-600 ring-slate-200' }}">{{ $e->status_label }}</span></td>
                                <td class="px-5 py-3 text-slate-600">{{ $e->assignee?->name ?? '—' }}</td>
                                <td class="px-5 py-3 text-slate-500">{{ $e->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-5 py-8 text-center text-slate-500">No enquiries yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    @endif

    {{-- Website content shortcuts --}}
    @if($content)
        <h2 class="mt-8 text-sm font-bold uppercase tracking-[0.18em] text-slate-400">Website</h2>
        <div class="mt-3 grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
            @foreach ($content as [$label, $value, $href, $icon])
                <a href="{{ $href }}" class="card card-hover flex items-center gap-4 !p-4">
                    <span class="icon-tile h-10 w-10"><x-site.icon :name="$icon" class="h-5 w-5" /></span>
                    <span><span class="block text-2xl font-extrabold tabular-nums text-ink">{{ $value }}</span><span class="block text-xs text-slate-500">{{ $label }}</span></span>
                </a>
            @endforeach
        </div>
    @endif
@endsection
