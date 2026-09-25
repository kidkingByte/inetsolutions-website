@extends('layouts.admin')

@php
    $area = request('area');
    $title = $area === 'support' ? 'Support tickets' : ($area === 'leads' ? 'Sales leads' : 'Enquiries');
    $statusColors = ['new' => 'bg-accent-50 text-accent-700 ring-accent-200', 'contacted' => 'bg-brand-50 text-brand-700 ring-brand-200', 'qualified' => 'bg-violet-50 text-violet-700 ring-violet-200', 'installation_scheduled' => 'bg-amber-50 text-amber-700 ring-amber-200', 'installed' => 'bg-emerald-50 text-emerald-700 ring-emerald-200', 'converted' => 'bg-emerald-50 text-emerald-700 ring-emerald-200', 'rejected' => 'bg-slate-100 text-slate-600 ring-slate-200', 'closed' => 'bg-slate-100 text-slate-600 ring-slate-200'];
    $quick = ['' => 'All', 'open' => 'Open', 'new' => 'New', 'contacted' => 'Contacted', 'converted' => 'Converted'];
@endphp

@section('title', $title)
@section('heading', $title)

@section('content')
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <nav class="flex flex-wrap gap-2" aria-label="Status">
            @foreach ($quick as $value => $label)
                <a href="{{ request()->fullUrlWithQuery(['status' => $value ?: null, 'page' => null]) }}" class="chip !py-1.5 {{ (string) request('status') === $value ? 'chip-active' : '' }}">{{ $label }}</a>
            @endforeach
            <a href="{{ request()->fullUrlWithQuery(['assigned' => request('assigned') === 'me' ? null : 'me', 'page' => null]) }}" class="chip !py-1.5 {{ request('assigned') === 'me' ? 'chip-active' : '' }}">Assigned to me</a>
        </nav>
        @can('leads.export')
            <a href="{{ route('admin.enquiries.export', request()->query()) }}" class="btn-secondary !py-2.5"><x-site.icon name="document" class="h-4 w-4" /> Export CSV</a>
        @endcan
    </div>

    <form method="GET" class="card mt-4 grid gap-3 !p-4 sm:grid-cols-2 lg:grid-cols-5">
        @if($area)<input type="hidden" name="area" value="{{ $area }}">@endif
        <label class="sr-only" for="q">Search</label>
        <input id="q" name="q" value="{{ request('q') }}" placeholder="Search name, phone or email" class="field lg:col-span-2">
        <select name="type" class="field" aria-label="Type">
            <option value="">All types</option>
            @foreach ($types as $key => $label)
                <option value="{{ $key }}" @selected(request('type') === $key)>{{ $label }}</option>
            @endforeach
        </select>
        <select name="assigned" class="field" aria-label="Assigned to">
            <option value="">Anyone</option>
            <option value="me" @selected(request('assigned') === 'me')>Me</option>
            <option value="none" @selected(request('assigned') === 'none')>Unassigned</option>
            @foreach ($staff as $member)
                <option value="{{ $member->id }}" @selected(request('assigned') == $member->id)>{{ $member->name }}</option>
            @endforeach
        </select>
        <div class="flex gap-2">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <button class="btn-primary flex-1 !py-2.5">Filter</button>
            <a href="{{ route('admin.enquiries.index', array_filter(['area' => $area])) }}" class="btn-secondary !px-4 !py-2.5" aria-label="Reset filters"><x-site.icon name="x" class="h-4 w-4" /></a>
        </div>
    </form>

    <div class="card mt-4 !p-0">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[56rem] text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-5 py-3 font-semibold">Contact</th>
                        <th class="px-5 py-3 font-semibold">Type</th>
                        <th class="px-5 py-3 font-semibold">Location</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                        <th class="px-5 py-3 font-semibold">Assigned</th>
                        <th class="px-5 py-3 font-semibold">Received</th>
                        <th class="px-5 py-3"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($enquiries as $enquiry)
                        @php $canManage = auth()->user()->can($enquiry->permission('manage')); @endphp
                        <tr class="hover:bg-slate-50/60">
                            <td class="px-5 py-3">
                                <a href="{{ route('admin.enquiries.show', $enquiry) }}" class="font-semibold text-ink hover:text-brand-700">{{ $enquiry->full_name ?: 'Unnamed visitor' }}</a>
                                <span class="block text-xs text-slate-500">{{ $enquiry->phone ?: $enquiry->email ?: '—' }}</span>
                            </td>
                            <td class="px-5 py-3 text-slate-600">{{ $enquiry->type_label }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ collect([$enquiry->ward, $enquiry->district, $enquiry->region])->filter()->implode(', ') ?: '—' }}</td>
                            <td class="px-5 py-3">
                                @if($canManage)
                                    {{-- Quick status change straight from the list --}}
                                    <form method="POST" action="{{ route('admin.enquiries.update', $enquiry) }}">
                                        @csrf @method('PUT')
                                        <label class="sr-only" for="status-{{ $enquiry->id }}">Status</label>
                                        <select id="status-{{ $enquiry->id }}" name="status" onchange="this.form.submit()" class="rounded-full border-0 py-1 pl-3 pr-8 text-xs font-semibold ring-1 focus:ring-2 focus:ring-brand-600 {{ $statusColors[$enquiry->status] ?? '' }}">
                                            @foreach ($statuses as $s)
                                                <option value="{{ $s }}" @selected($enquiry->status === $s)>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                @else
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ $statusColors[$enquiry->status] ?? '' }}">{{ $enquiry->status_label }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-slate-600">{{ $enquiry->assignee?->name ?? 'Unassigned' }}</td>
                            <td class="whitespace-nowrap px-5 py-3 text-slate-500" title="{{ $enquiry->created_at }}">{{ $enquiry->created_at->diffForHumans() }}</td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    @if($enquiry->phone)
                                        <a href="tel:{{ $enquiry->phone }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 hover:bg-brand-50 hover:text-brand-700" title="Call {{ $enquiry->phone }}" aria-label="Call"><x-site.icon name="phone" class="h-4 w-4" /></a>
                                        <a href="https://wa.me/{{ $enquiry->whatsapp_number }}" target="_blank" rel="noopener" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 hover:bg-emerald-50 hover:text-emerald-700" title="WhatsApp" aria-label="WhatsApp"><x-site.icon name="whatsapp" class="h-4 w-4" /></a>
                                    @endif
                                    <a href="{{ route('admin.enquiries.show', $enquiry) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 hover:text-ink" aria-label="Open"><x-site.icon name="arrow-right" class="h-4 w-4" /></a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-12 text-center text-slate-500">No enquiries match these filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $enquiries->links() }}</div>
@endsection
