@extends('layouts.admin')

@php $isEdit = $faq->exists; @endphp

@section('title', $isEdit ? 'Edit FAQ' : 'New FAQ')
@section('heading', $isEdit ? 'Edit FAQ' : 'New FAQ')

@section('content')
<form method="POST" action="{{ $isEdit ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}"
      class="bg-white rounded-xl border border-slate-200 p-5 max-w-2xl space-y-5">
    @csrf
    @if ($isEdit) @method('PUT') @endif

    <div>
        <label class="block text-sm font-medium">Question *</label>
        <input name="question" value="{{ old('question', $faq->question) }}" required class="mt-1 w-full rounded-lg border-slate-300 focus:border-brand-600 focus:ring-brand-600">
    </div>
    <div>
        <label class="block text-sm font-medium">Answer *</label>
        <textarea name="answer" rows="5" required class="mt-1 w-full rounded-lg border-slate-300">{{ old('answer', $faq->answer) }}</textarea>
    </div>
    <div class="grid sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium">Category</label>
            <input name="category" value="{{ old('category', $faq->category) }}" class="mt-1 w-full rounded-lg border-slate-300">
        </div>
        <div>
            <label class="block text-sm font-medium">Sort order</label>
            <input type="number" min="0" name="sort_order" value="{{ old('sort_order', $faq->sort_order ?? 0) }}" class="mt-1 w-full rounded-lg border-slate-300">
        </div>
    </div>
    <label class="inline-flex items-center gap-2 text-sm">
        <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $faq->is_published)) class="rounded border-slate-300 text-brand-600 focus:ring-brand-600"> Published
    </label>

    <div class="flex gap-3 pt-2">
        <button class="rounded-lg bg-brand-700 px-5 py-2 text-sm font-semibold text-white hover:bg-brand-800">{{ $isEdit ? 'Update' : 'Create' }}</button>
        <a href="{{ route('admin.faqs.index') }}" class="rounded-lg border border-slate-300 px-5 py-2 text-sm hover:bg-slate-50">Cancel</a>
    </div>
</form>
@endsection
