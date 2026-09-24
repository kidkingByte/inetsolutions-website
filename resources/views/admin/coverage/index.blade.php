@extends('layouts.admin')

@section('title', 'Coverage')
@section('heading', 'Coverage Areas')

@section('content')
    <div class="flex items-center justify-between mb-4 gap-3 flex-wrap">
        <form method="GET" class="flex items-center gap-2">
            <input name="q" value="{{ request('q') }}" placeholder="Search region / district / ward" class="rounded-lg border-slate-300 text-sm w-64">
            <button class="rounded-lg border border-slate-300 px-3 py-1.5 text-sm hover:bg-slate-50">Search</button>
        </form>
        <a href="{{ route('admin.coverage.create') }}" class="rounded-lg bg-brand-700 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-800">+ Add Area</a>
    </div>

    @php $badges = ['available'=>'bg-emerald-100 text-emerald-700','coming_soon'=>'bg-amber-100 text-amber-700','under_expansion'=>'bg-sky-100 text-sky-700','not_available'=>'bg-slate-100 text-slate-500']; @endphp

    <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-left text-slate-500 bg-slate-50">
                <tr>
                    <th class="px-5 py-3 font-medium">Region</th>
                    <th class="px-5 py-3 font-medium">District</th>
                    <th class="px-5 py-3 font-medium">Ward</th>
                    <th class="px-5 py-3 font-medium">Technology</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($areas as $area)
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-3 font-medium text-slate-800">{{ $area->region }}</td>
                        <td class="px-5 py-3">{{ $area->district ?: '—' }}</td>
                        <td class="px-5 py-3">{{ $area->ward ?: '—' }}</td>
                        <td class="px-5 py-3">{{ $area->technology ?: '—' }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-block rounded-full px-2 py-0.5 text-xs {{ $badges[$area->status] ?? 'bg-slate-100' }}">{{ str_replace('_',' ',ucfirst($area->status)) }}</span>
                        </td>
                        <td class="px-5 py-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.coverage.edit', $area) }}" class="text-brand-700 hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.coverage.destroy', $area) }}" class="inline ml-2" onsubmit="return confirm('Delete this area?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-6 text-center text-slate-400">No coverage areas found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $areas->links() }}</div>
@endsection
