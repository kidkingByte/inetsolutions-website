@extends('layouts.admin')

@php $isEdit = $post->exists; @endphp

@section('title', $isEdit ? 'Edit Post' : 'New Post')
@section('heading', $isEdit ? 'Edit Post' : 'New Post')

@section('content')
<form method="POST" enctype="multipart/form-data" action="{{ $isEdit ? route('admin.posts.update', $post) : route('admin.posts.store') }}"
      class="bg-white rounded-xl border border-slate-200 p-5 max-w-3xl space-y-5">
    @csrf
    @if ($isEdit) @method('PUT') @endif

    <div>
        <label class="block text-sm font-medium">Title *</label>
        <input name="title" value="{{ old('title', $post->title) }}" required class="mt-1 w-full rounded-lg border-slate-300 focus:border-brand-600 focus:ring-brand-600">
    </div>

    <div class="grid sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium">Category</label>
            <input name="category" value="{{ old('category', $post->category) }}" class="mt-1 w-full rounded-lg border-slate-300">
        </div>
        <div>
            <label class="block text-sm font-medium">Author</label>
            <input name="author" value="{{ old('author', $post->author) }}" class="mt-1 w-full rounded-lg border-slate-300">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium">Excerpt</label>
        <textarea name="excerpt" rows="2" class="mt-1 w-full rounded-lg border-slate-300">{{ old('excerpt', $post->excerpt) }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium">Body</label>
        <textarea name="body" rows="12" class="mt-1 w-full rounded-lg border-slate-300 font-mono text-sm">{{ old('body', $post->body) }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium">Cover image</label>
        <input type="file" name="cover_image" accept="image/*" class="mt-1 block w-full text-sm text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-sm file:font-medium">
        @if ($post->cover_image)
            <p class="text-xs text-slate-400 mt-1">Current: {{ $post->cover_image }}</p>
        @endif
    </div>

    <div class="grid sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium">Meta title</label>
            <input name="meta_title" value="{{ old('meta_title', $post->meta_title) }}" class="mt-1 w-full rounded-lg border-slate-300">
        </div>
        <div>
            <label class="block text-sm font-medium">Meta description</label>
            <input name="meta_description" value="{{ old('meta_description', $post->meta_description) }}" class="mt-1 w-full rounded-lg border-slate-300">
        </div>
    </div>

    <label class="inline-flex items-center gap-2 text-sm">
        <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $post->is_published)) class="rounded border-slate-300 text-brand-600 focus:ring-brand-600"> Published
    </label>

    <div class="flex gap-3 pt-2">
        <button class="rounded-lg bg-brand-700 px-5 py-2 text-sm font-semibold text-white hover:bg-brand-800">{{ $isEdit ? 'Update' : 'Create' }}</button>
        <a href="{{ route('admin.posts.index') }}" class="rounded-lg border border-slate-300 px-5 py-2 text-sm hover:bg-slate-50">Cancel</a>
    </div>
</form>
@endsection
