@extends('layouts.admin')

@section('title', 'Enquiries')
@section('heading', 'Enquiries / Leads')

@section('content')
    <form method="GET" class="bg-white rounded-xl border border-slate-200 p-4 mb-4 flex flex-wrap items-end gap-3">
        <div>
            <label class="block text-xs font-medium text-slate-500">Search</label>
            <input name="q" value="{{ request('q') }}" placeholder="Name / phone / email" class="mt-1 rounded-lg border-slate-300 text-sm">
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-500">Type</label>
            <select name="type" class="mt-1 rounded-lg border-slate-300 text-sm">
                <option value="">All types</option>
                @foreach ($types as $val => $label)
                    <option value="{{ $val }}" @selected(request('type') == $val)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-500">Status</label>
            <select name="status" class="mt-1 rounded-lg border-slate-300 text-sm">
                <option value="">All statuses</option>
                @foreach ($statuses as $val)
                    <option value="{{ $val }}" @selected(request('status') == $val)>{{ ucfirst(str_replace('_',' ',$val)) }}</option>
                @endforeach
            </select>
        </div>
        <button class="rounded-lg bg-brand-700 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-800">Filter</button>
    </form>

    <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-left text-slate-500 bg-slate-50">
                <tr>
                    <th class="px-5 py-3 font-medium">Name</th>
                    <th class="px-5 py-3 font-medium">Type</th>
                    <th class="px-5 py-3 font-medium">Contact</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium">Date</th>
                    <th class="px-5 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($enquiries as $enquiry)
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-3 font-medium text-slate-800">
                            <a href="{{ route('admin.enquiries.show', $enquiry) }}" class="text-brand-700 hover:underline">{{ $enquiry->full_name }}</a>
                        </td>
                        <td class="px-5 py-3">{{ $enquiry->type_label }}</td>
                        <td class="px-5 py-3 text-slate-500">{{ $enquiry->phone }}<br><span class="text-xs">{{ $enquiry->email }}</span></td>
                        <td class="px-5 py-3">
                            <span class="inline-block rounded-full px-2 py-0.5 text-xs {{ $enquiry->status === 'new' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600' }}">{{ ucfirst(str_replace('_',' ',$enquiry->status)) }}</span>
                        </td>
                        <td class="px-5 py-3 text-slate-500">{{ $enquiry->created_at->format('d M Y') }}</td>
                        <td class="px-5 py-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.enquiries.show', $enquiry) }}" class="text-brand-700 hover:underline">View</a>
                            <form method="POST" action="{{ route('admin.enquiries.destroy', $enquiry) }}" class="inline ml-2" onsubmit="return confirm('Delete this enquiry?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-6 text-center text-slate-400">No enquiries found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $enquiries->withQueryString()->links() }}</div>
@endsection
