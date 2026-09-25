@extends('layouts.admin')

@section('title', 'Audit log')
@section('heading', 'Audit log')

@section('content')
    @php
        $eventColors = ['created' => 'bg-emerald-50 text-emerald-700', 'updated' => 'bg-brand-50 text-brand-700', 'deleted' => 'bg-red-50 text-red-700', 'login' => 'bg-slate-100 text-slate-700', 'logout' => 'bg-slate-100 text-slate-700', 'login_failed' => 'bg-amber-50 text-amber-700', 'exported' => 'bg-violet-50 text-violet-700'];
    @endphp

    <p class="text-slate-500">Every change made in this panel, plus sign-ins and exports. Entries can't be edited or deleted.</p>

    <form method="GET" class="card mt-4 grid gap-3 !p-4 sm:grid-cols-2 lg:grid-cols-4">
        <select name="event" class="field" aria-label="Action">
            <option value="">All actions</option>
            @foreach ($events as $key => $label)<option value="{{ $key }}" @selected(request('event') === $key)>{{ $label }}</option>@endforeach
        </select>
        <select name="user" class="field" aria-label="Staff member">
            <option value="">Everyone</option>
            @foreach ($users as $u)<option value="{{ $u->id }}" @selected(request('user') == $u->id)>{{ $u->name }}</option>@endforeach
        </select>
        <select name="type" class="field" aria-label="Record type">
            <option value="">All records</option>
            @foreach ($types as $t)<option value="{{ $t }}" @selected(request('type') === $t)>{{ $t }}</option>@endforeach
        </select>
        <div class="flex gap-2">
            <button class="btn-primary flex-1 !py-2.5">Filter</button>
            <a href="{{ route('admin.audit.index') }}" class="btn-secondary !px-4 !py-2.5" aria-label="Reset filters"><x-site.icon name="x" class="h-4 w-4" /></a>
        </div>
    </form>

    <div class="card mt-4 !p-0">
        <ul class="divide-y divide-slate-100">
            @forelse ($logs as $log)
                <li x-data="{ open: false }" class="px-5 py-3">
                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-sm">
                        <span class="w-36 shrink-0 text-xs text-slate-500" title="{{ $log->created_at }}">{{ $log->created_at->format('d M Y, H:i') }}</span>
                        <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $eventColors[$log->event] ?? 'bg-slate-100 text-slate-700' }}">{{ $log->event_label }}</span>
                        <span class="font-semibold text-ink">{{ $log->user?->name ?? 'Unknown' }}</span>
                        <span class="min-w-0 flex-1 truncate text-slate-600">
                            @if($log->subject_type_label)<span class="text-slate-400">{{ $log->subject_type_label }}:</span>@endif
                            @if($log->auditable_type === \App\Models\Enquiry::class && $log->event !== 'deleted')
                                <a href="{{ route('admin.enquiries.show', $log->auditable_id) }}" class="text-brand-700 hover:underline">{{ $log->description }}</a>
                            @else
                                {{ $log->description }}
                            @endif
                        </span>
                        @if($log->changes)
                            <button type="button" @click="open = ! open" :aria-expanded="open" class="inline-flex min-h-9 items-center gap-1 text-xs font-semibold text-brand-700">
                                <span x-text="open ? 'Hide details' : 'Details'">Details</span><x-site.icon name="chevron-down" class="h-3.5 w-3.5 transition" ::class="open && 'rotate-180'" />
                            </button>
                        @endif
                    </div>
                    @if($log->changes)
                        <div x-show="open" x-cloak class="mt-3 overflow-x-auto rounded-xl bg-slate-50 p-3">
                            <table class="w-full text-xs">
                                <thead class="text-left text-slate-500"><tr><th class="pb-1 pr-4 font-semibold">Field</th>@if($log->event === 'updated')<th class="pb-1 pr-4 font-semibold">Before</th>@endif<th class="pb-1 font-semibold">{{ $log->event === 'updated' ? 'After' : 'Value' }}</th></tr></thead>
                                <tbody>
                                    @foreach ($log->changes as $field => $change)
                                        <tr class="align-top">
                                            <td class="py-0.5 pr-4 font-medium text-ink">{{ $field }}</td>
                                            @if(is_array($change) && $log->event === 'updated')
                                                <td class="max-w-xs break-words py-0.5 pr-4 text-red-700 line-through decoration-red-300">{{ is_scalar($change['old'] ?? null) ? Str::limit((string) $change['old'], 140) : json_encode($change['old'] ?? null) }}</td>
                                            @endif
                                            <td class="max-w-xs break-words py-0.5 text-emerald-700">{{ is_array($change) ? (is_scalar($change['new'] ?? null) ? Str::limit((string) $change['new'], 140) : json_encode($change['new'] ?? null)) : $change }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </li>
            @empty
                <li class="px-5 py-12 text-center text-slate-500">No activity recorded yet.</li>
            @endforelse
        </ul>
    </div>

    <div class="mt-4">{{ $logs->links() }}</div>
@endsection
