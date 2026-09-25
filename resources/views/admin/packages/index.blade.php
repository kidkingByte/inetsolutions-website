@extends('layouts.admin')

@section('title', 'Packages')
@section('heading', 'Packages')

@section('content')
    <div class="flex items-center justify-between mb-4 gap-3 flex-wrap">
        <form method="GET" class="flex items-center gap-2">
            <select name="category" class="rounded-lg border-slate-300 text-sm focus:border-brand-600 focus:ring-brand-600">
                <option value="">All categories</option>
                @foreach (['home' => 'Home', 'business' => 'Business', 'enterprise' => 'Enterprise'] as $val => $label)
                    <option value="{{ $val }}" @selected(request('category') == $val)>{{ $label }}</option>
                @endforeach
            </select>
            <button class="rounded-lg border border-slate-300 px-3 py-1.5 text-sm hover:bg-slate-50">Filter</button>
        </form>
        <a href="{{ route('admin.packages.create') }}" class="rounded-lg bg-brand-700 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-800">+ Add Package</a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-left text-slate-500 bg-slate-50">
                <tr>
                    <th class="px-5 py-3 font-medium">Name</th>
                    <th class="px-5 py-3 font-medium">Category</th>
                    <th class="px-5 py-3 font-medium">Speed</th>
                    <th class="px-5 py-3 font-medium">Price</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($packages as $package)
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-3 font-medium text-slate-800">
                            {{ $package->name }}
                            @if ($package->is_featured)<span class="ml-1 text-xs text-amber-600">★</span>@endif
                        </td>
                        <td class="px-5 py-3 capitalize">{{ $package->category }}</td>
                        <td class="px-5 py-3">{{ $package->speed ?: '—' }}</td>
                        <td class="px-5 py-3">{{ $package->price !== null ? number_format($package->price) : 'On request' }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-block rounded-full px-2 py-0.5 text-xs {{ $package->is_published ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ $package->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.packages.edit', $package) }}" class="text-brand-700 hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.packages.destroy', $package) }}" class="inline ml-2" onsubmit="return confirm('Delete this package?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-6 text-center text-slate-400">No packages found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $packages->links() }}</div>
@endsection
