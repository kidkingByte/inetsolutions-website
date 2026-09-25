@extends('layouts.admin')

@php $isEdit = $promotion->exists; @endphp

@section('title', $isEdit ? 'Edit Promotion' : 'New Promotion')
@section('heading', $isEdit ? 'Edit Promotion' : 'New Promotion')

@section('content')
<form method="POST" enctype="multipart/form-data" action="{{ $isEdit ? route('admin.promotions.update', $promotion) : route('admin.promotions.store') }}"
      class="bg-white rounded-xl border border-slate-200 p-5 max-w-2xl space-y-5">
    @csrf
    @if ($isEdit) @method('PUT') @endif

    <div>
        <label class="block text-sm font-medium">Title *</label>
        <input name="title" value="{{ old('title', $promotion->title) }}" required class="mt-1 w-full rounded-lg border-slate-300 focus:border-brand-600 focus:ring-brand-600">
    </div>
    <div>
        <label class="block text-sm font-medium">Description</label>
        <textarea name="description" rows="3" class="mt-1 w-full rounded-lg border-slate-300">{{ old('description', $promotion->description) }}</textarea>
    </div>
    <div class="grid sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium">Link</label>
            <input name="link" value="{{ old('link', $promotion->link) }}" class="mt-1 w-full rounded-lg border-slate-300">
        </div>
        <div>
            <label class="block text-sm font-medium">Placement *</label>
            <select name="placement" class="mt-1 w-full rounded-lg border-slate-300">
                @foreach (['homepage'=>'Homepage','packages'=>'Packages','popup'=>'Popup'] as $val => $label)
                    <option value="{{ $val }}" @selected(old('placement', $promotion->placement) == $val)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium">Starts at</label>
            <input type="date" name="starts_at" value="{{ old('starts_at', $promotion->starts_at?->format('Y-m-d')) }}" class="mt-1 w-full rounded-lg border-slate-300">
        </div>
        <div>
            <label class="block text-sm font-medium">Ends at</label>
            <input type="date" name="ends_at" value="{{ old('ends_at', $promotion->ends_at?->format('Y-m-d')) }}" class="mt-1 w-full rounded-lg border-slate-300">
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium">Image</label>
        <input type="file" name="image" accept="image/*" class="mt-1 block w-full text-sm text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-sm file:font-medium">
    </div>
    <label class="inline-flex items-center gap-2 text-sm">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $promotion->is_active)) class="rounded border-slate-300 text-brand-600 focus:ring-brand-600"> Active
    </label>

    <div class="flex gap-3 pt-2">
        <button class="rounded-lg bg-brand-700 px-5 py-2 text-sm font-semibold text-white hover:bg-brand-800">{{ $isEdit ? 'Update' : 'Create' }}</button>
        <a href="{{ route('admin.promotions.index') }}" class="rounded-lg border border-slate-300 px-5 py-2 text-sm hover:bg-slate-50">Cancel</a>
    </div>
</form>
@endsection
