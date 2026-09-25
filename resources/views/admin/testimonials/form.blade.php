@extends('layouts.admin')

@php $isEdit = $testimonial->exists; @endphp

@section('title', $isEdit ? 'Edit Testimonial' : 'New Testimonial')
@section('heading', $isEdit ? 'Edit Testimonial' : 'New Testimonial')

@section('content')
<form method="POST" action="{{ $isEdit ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}"
      class="bg-white rounded-xl border border-slate-200 p-5 max-w-2xl space-y-5">
    @csrf
    @if ($isEdit) @method('PUT') @endif

    <div class="grid sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium">Customer name *</label>
            <input name="customer_name" value="{{ old('customer_name', $testimonial->customer_name) }}" required class="mt-1 w-full rounded-lg border-slate-300 focus:border-brand-600 focus:ring-brand-600">
        </div>
        <div>
            <label class="block text-sm font-medium">Organization</label>
            <input name="organization" value="{{ old('organization', $testimonial->organization) }}" class="mt-1 w-full rounded-lg border-slate-300">
        </div>
        <div>
            <label class="block text-sm font-medium">Rating (1-5)</label>
            <input type="number" min="1" max="5" name="rating" value="{{ old('rating', $testimonial->rating) }}" class="mt-1 w-full rounded-lg border-slate-300">
        </div>
        <div>
            <label class="block text-sm font-medium">Sort order</label>
            <input type="number" min="0" name="sort_order" value="{{ old('sort_order', $testimonial->sort_order ?? 0) }}" class="mt-1 w-full rounded-lg border-slate-300">
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium">Quote *</label>
        <textarea name="quote" rows="4" required class="mt-1 w-full rounded-lg border-slate-300">{{ old('quote', $testimonial->quote) }}</textarea>
    </div>
    <label class="inline-flex items-center gap-2 text-sm">
        <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $testimonial->is_published)) class="rounded border-slate-300 text-brand-600 focus:ring-brand-600"> Published
    </label>

    <div class="flex gap-3 pt-2">
        <button class="rounded-lg bg-brand-700 px-5 py-2 text-sm font-semibold text-white hover:bg-brand-800">{{ $isEdit ? 'Update' : 'Create' }}</button>
        <a href="{{ route('admin.testimonials.index') }}" class="rounded-lg border border-slate-300 px-5 py-2 text-sm hover:bg-slate-50">Cancel</a>
    </div>
</form>
@endsection
