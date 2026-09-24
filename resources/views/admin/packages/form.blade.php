@extends('layouts.admin')

@php $isEdit = $package->exists; @endphp

@section('title', $isEdit ? 'Edit Package' : 'New Package')
@section('heading', $isEdit ? 'Edit Package' : 'New Package')

@section('content')
<form method="POST" action="{{ $isEdit ? route('admin.packages.update', $package) : route('admin.packages.store') }}"
      class="bg-white rounded-xl border border-slate-200 p-5 max-w-3xl space-y-5"
      x-data="{ features: {{ Js::from(old('features', $package->feature_list ?? [])) }} }">
    @csrf
    @if ($isEdit) @method('PUT') @endif

    <div class="grid sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium">Name *</label>
            <input name="name" value="{{ old('name', $package->name) }}" required class="mt-1 w-full rounded-lg border-slate-300 focus:border-brand-600 focus:ring-brand-600">
        </div>
        <div>
            <label class="block text-sm font-medium">Category *</label>
            <select name="category" class="mt-1 w-full rounded-lg border-slate-300 focus:border-brand-600 focus:ring-brand-600">
                @foreach (['home' => 'Home', 'business' => 'Business', 'enterprise' => 'Enterprise'] as $val => $label)
                    <option value="{{ $val }}" @selected(old('category', $package->category) == $val)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium">Speed (headline)</label>
            <input name="speed" value="{{ old('speed', $package->speed) }}" placeholder="e.g. Up to 20 Mbps" class="mt-1 w-full rounded-lg border-slate-300">
        </div>
        <div>
            <label class="block text-sm font-medium">Validity</label>
            <input name="validity" value="{{ old('validity', $package->validity) }}" placeholder="e.g. Monthly" class="mt-1 w-full rounded-lg border-slate-300">
        </div>
        <div>
            <label class="block text-sm font-medium">Download speed</label>
            <input name="download_speed" value="{{ old('download_speed', $package->download_speed) }}" class="mt-1 w-full rounded-lg border-slate-300">
        </div>
        <div>
            <label class="block text-sm font-medium">Upload speed</label>
            <input name="upload_speed" value="{{ old('upload_speed', $package->upload_speed) }}" class="mt-1 w-full rounded-lg border-slate-300">
        </div>
        <div>
            <label class="block text-sm font-medium">Price (TZS)</label>
            <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $package->price) }}" class="mt-1 w-full rounded-lg border-slate-300">
            <p class="text-xs text-slate-400 mt-1">Leave blank to show "Price on request".</p>
        </div>
        <div>
            <label class="block text-sm font-medium">Installation fee (TZS)</label>
            <input type="number" step="0.01" min="0" name="installation_fee" value="{{ old('installation_fee', $package->installation_fee) }}" class="mt-1 w-full rounded-lg border-slate-300">
        </div>
        <div>
            <label class="block text-sm font-medium">Recommended users</label>
            <input type="number" min="1" name="recommended_users" value="{{ old('recommended_users', $package->recommended_users) }}" class="mt-1 w-full rounded-lg border-slate-300">
        </div>
        <div>
            <label class="block text-sm font-medium">Sort order</label>
            <input type="number" min="0" name="sort_order" value="{{ old('sort_order', $package->sort_order ?? 0) }}" class="mt-1 w-full rounded-lg border-slate-300">
        </div>
        <div>
            <label class="block text-sm font-medium">Router info</label>
            <input name="router_info" value="{{ old('router_info', $package->router_info) }}" class="mt-1 w-full rounded-lg border-slate-300">
        </div>
        <div>
            <label class="block text-sm font-medium">Installation time</label>
            <input name="installation_time" value="{{ old('installation_time', $package->installation_time) }}" class="mt-1 w-full rounded-lg border-slate-300">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium">Description</label>
        <textarea name="description" rows="2" class="mt-1 w-full rounded-lg border-slate-300">{{ old('description', $package->description) }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-medium">Fair usage policy</label>
        <textarea name="fair_usage_policy" rows="2" class="mt-1 w-full rounded-lg border-slate-300">{{ old('fair_usage_policy', $package->fair_usage_policy) }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Features</label>
        <div class="space-y-2">
            <template x-for="(feature, index) in features" :key="index">
                <div class="flex gap-2">
                    <input x-model="features[index]" :name="`features[${index}]`" class="flex-1 rounded-lg border-slate-300">
                    <button type="button" @click="features.splice(index, 1)" class="rounded-lg border border-red-200 text-red-600 px-3 text-sm">✕</button>
                </div>
            </template>
            <button type="button" @click="features.push('')" class="rounded-lg border border-slate-300 px-3 py-1.5 text-sm hover:bg-slate-50">+ Add feature</button>
        </div>
    </div>

    <div class="flex flex-wrap gap-6">
        <label class="inline-flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $package->is_featured)) class="rounded border-slate-300 text-brand-600 focus:ring-brand-600"> Featured
        </label>
        <label class="inline-flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $package->is_published)) class="rounded border-slate-300 text-brand-600 focus:ring-brand-600"> Published
        </label>
    </div>

    <div class="flex gap-3 pt-2">
        <button class="rounded-lg bg-brand-700 px-5 py-2 text-sm font-semibold text-white hover:bg-brand-800">{{ $isEdit ? 'Update' : 'Create' }}</button>
        <a href="{{ route('admin.packages.index') }}" class="rounded-lg border border-slate-300 px-5 py-2 text-sm hover:bg-slate-50">Cancel</a>
    </div>
</form>
@endsection
