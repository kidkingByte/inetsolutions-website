@extends('layouts.admin')

@section('title', 'Network Status')
@section('heading', 'Network Status')

@php
    $badges = ['operational'=>'bg-emerald-100 text-emerald-700','degraded'=>'bg-amber-100 text-amber-700','outage'=>'bg-red-100 text-red-700','maintenance'=>'bg-sky-100 text-sky-700'];
@endphp

@section('content')
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200">
            <div class="px-5 py-4 border-b border-slate-100"><h2 class="font-semibold">Services</h2></div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="text-left text-slate-500 bg-slate-50">
                        <tr>
                            <th class="px-5 py-3 font-medium">Service</th>
                            <th class="px-5 py-3 font-medium">Status</th>
                            <th class="px-5 py-3 font-medium">Message</th>
                            <th class="px-5 py-3 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($statuses as $s)
                            <tr class="hover:bg-slate-50">
                                <td class="px-5 py-3 font-medium text-slate-800">{{ $s->service }}</td>
                                <td class="px-5 py-3"><span class="inline-block rounded-full px-2 py-0.5 text-xs {{ $badges[$s->status] ?? 'bg-slate-100' }}">{{ ucfirst($s->status) }}</span></td>
                                <td class="px-5 py-3 text-slate-500">{{ $s->message ?: '—' }}</td>
                                <td class="px-5 py-3 text-right">
                                    <form method="POST" action="{{ route('admin.network-status.destroy', $s) }}" class="inline" onsubmit="return confirm('Remove this service?')">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600 hover:underline">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-5 py-6 text-center text-slate-400">No services configured.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 h-fit">
            <div class="px-5 py-4 border-b border-slate-100"><h2 class="font-semibold">Add Service</h2></div>
            <form method="POST" action="{{ route('admin.network-status.store') }}" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium">Service *</label>
                    <input name="service" value="{{ old('service') }}" required class="mt-1 w-full rounded-lg border-slate-300">
                </div>
                <div>
                    <label class="block text-sm font-medium">Status *</label>
                    <select name="status" class="mt-1 w-full rounded-lg border-slate-300">
                        @foreach (['operational'=>'Operational','degraded'=>'Degraded','outage'=>'Outage','maintenance'=>'Maintenance'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('status') == $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium">Message</label>
                    <input name="message" value="{{ old('message') }}" class="mt-1 w-full rounded-lg border-slate-300">
                </div>
                <div>
                    <label class="block text-sm font-medium">Sort order</label>
                    <input type="number" min="0" name="sort_order" value="{{ old('sort_order', 0) }}" class="mt-1 w-full rounded-lg border-slate-300">
                </div>
                <button class="w-full rounded-lg bg-brand-700 px-5 py-2 text-sm font-semibold text-white hover:bg-brand-800">Add</button>
            </form>
        </div>
    </div>
@endsection
