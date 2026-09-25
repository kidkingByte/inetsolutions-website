@extends('layouts.admin')

@section('title', 'Promotions')
@section('heading', 'Promotions')

@section('content')
    <div class="flex justify-end mb-4">
        <a href="{{ route('admin.promotions.create') }}" class="rounded-lg bg-brand-700 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-800">+ Add Promotion</a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-left text-slate-500 bg-slate-50">
                <tr>
                    <th class="px-5 py-3 font-medium">Title</th>
                    <th class="px-5 py-3 font-medium">Placement</th>
                    <th class="px-5 py-3 font-medium">Window</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($promotions as $promo)
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-3 font-medium text-slate-800">{{ $promo->title }}</td>
                        <td class="px-5 py-3 capitalize">{{ $promo->placement }}</td>
                        <td class="px-5 py-3 text-slate-500">
                            {{ $promo->starts_at?->format('d M Y') ?: '—' }} → {{ $promo->ends_at?->format('d M Y') ?: '—' }}
                        </td>
                        <td class="px-5 py-3">
                            <span class="inline-block rounded-full px-2 py-0.5 text-xs {{ $promo->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">{{ $promo->is_active ? 'Active' : 'Inactive' }}</span>
                        </td>
                        <td class="px-5 py-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.promotions.edit', $promo) }}" class="text-brand-700 hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.promotions.destroy', $promo) }}" class="inline ml-2" onsubmit="return confirm('Delete this promotion?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-6 text-center text-slate-400">No promotions yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $promotions->links() }}</div>
@endsection
