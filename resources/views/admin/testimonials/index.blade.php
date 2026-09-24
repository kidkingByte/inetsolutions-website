@extends('layouts.admin')

@section('title', 'Testimonials')
@section('heading', 'Testimonials')

@section('content')
    <div class="flex justify-end mb-4">
        <a href="{{ route('admin.testimonials.create') }}" class="rounded-lg bg-brand-700 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-800">+ Add Testimonial</a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-left text-slate-500 bg-slate-50">
                <tr>
                    <th class="px-5 py-3 font-medium">Customer</th>
                    <th class="px-5 py-3 font-medium">Organization</th>
                    <th class="px-5 py-3 font-medium">Quote</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($testimonials as $t)
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-3 font-medium text-slate-800">{{ $t->customer_name }}</td>
                        <td class="px-5 py-3">{{ $t->organization ?: '—' }}</td>
                        <td class="px-5 py-3 text-slate-500 max-w-xs truncate">{{ $t->quote }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-block rounded-full px-2 py-0.5 text-xs {{ $t->is_published ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">{{ $t->is_published ? 'Published' : 'Hidden' }}</span>
                        </td>
                        <td class="px-5 py-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.testimonials.edit', $t) }}" class="text-brand-700 hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.testimonials.destroy', $t) }}" class="inline ml-2" onsubmit="return confirm('Delete this testimonial?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-6 text-center text-slate-400">No testimonials yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $testimonials->links() }}</div>
@endsection
