@extends('layouts.admin')

@php $isEdit = $area->exists; @endphp

@section('title', $isEdit ? 'Edit Coverage Area' : 'New Coverage Area')
@section('heading', $isEdit ? 'Edit Coverage Area' : 'New Coverage Area')

@section('content')
<form method="POST" action="{{ $isEdit ? route('admin.coverage.update', $area) : route('admin.coverage.store') }}"
      class="bg-white rounded-xl border border-slate-200 p-5 max-w-2xl space-y-5">
    @csrf
    @if ($isEdit) @method('PUT') @endif

    <div class="grid sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium">Region *</label>
            <input name="region" value="{{ old('region', $area->region) }}" required list="tz-regions" class="mt-1 w-full rounded-lg border-slate-300 focus:border-brand-600 focus:ring-brand-600">
            <datalist id="tz-regions">
                @foreach (\App\Models\CoverageArea::TANZANIA_REGIONS as $r)<option value="{{ $r }}">@endforeach
            </datalist>
            <p class="mt-1 text-xs text-slate-500">Pick from the list so the website's location dropdowns group areas correctly.</p>
        </div>
        <div>
            <label class="block text-sm font-medium">District</label>
            <input name="district" value="{{ old('district', $area->district) }}" class="mt-1 w-full rounded-lg border-slate-300">
        </div>
        <div>
            <label class="block text-sm font-medium">Ward</label>
            <input name="ward" value="{{ old('ward', $area->ward) }}" class="mt-1 w-full rounded-lg border-slate-300">
        </div>
        <div>
            <label class="block text-sm font-medium">Street</label>
            <input name="street" value="{{ old('street', $area->street) }}" class="mt-1 w-full rounded-lg border-slate-300">
        </div>
        <div class="sm:col-span-2 rounded-xl bg-slate-50 p-4">
            <p class="text-sm font-medium">Map position <span class="font-normal text-slate-500">(optional)</span></p>
            <p class="mt-1 text-xs text-slate-500">Leave blank to show this area at its region's centre on the website map. For an exact pin, right-click the place in Google Maps and click the coordinates to copy them (e.g. -5.2459, 39.7666).</p>
            <div class="mt-3 grid gap-3 sm:grid-cols-2">
                <div>
                    <label class="block text-xs font-medium text-slate-600" for="latitude">Latitude</label>
                    <input id="latitude" name="latitude" inputmode="decimal" value="{{ old('latitude', $area->latitude) }}" placeholder="-6.1659" class="mt-1 w-full rounded-lg border-slate-300">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600" for="longitude">Longitude</label>
                    <input id="longitude" name="longitude" inputmode="decimal" value="{{ old('longitude', $area->longitude) }}" placeholder="39.2026" class="mt-1 w-full rounded-lg border-slate-300">
                </div>
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium">Service type</label>
            <input name="service_type" value="{{ old('service_type', $area->service_type) }}" placeholder="e.g. Home Fibre" class="mt-1 w-full rounded-lg border-slate-300">
        </div>
        <div>
            <label class="block text-sm font-medium">Technology</label>
            <input name="technology" value="{{ old('technology', $area->technology) }}" placeholder="e.g. Fibre / Wireless" class="mt-1 w-full rounded-lg border-slate-300">
        </div>
        <div>
            <label class="block text-sm font-medium">Status *</label>
            <select name="status" class="mt-1 w-full rounded-lg border-slate-300">
                @foreach (['available'=>'Available','coming_soon'=>'Coming Soon','under_expansion'=>'Under Expansion','not_available'=>'Not Available'] as $val => $label)
                    <option value="{{ $val }}" @selected(old('status', $area->status) == $val)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end">
            <label class="inline-flex items-center gap-2 text-sm">
                <input type="checkbox" name="installation_available" value="1" @checked(old('installation_available', $area->installation_available)) class="rounded border-slate-300 text-brand-600 focus:ring-brand-600"> Installation available
            </label>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium">Notes</label>
        <textarea name="notes" rows="3" class="mt-1 w-full rounded-lg border-slate-300">{{ old('notes', $area->notes) }}</textarea>
    </div>

    <div class="flex gap-3 pt-2">
        <button class="rounded-lg bg-brand-700 px-5 py-2 text-sm font-semibold text-white hover:bg-brand-800">{{ $isEdit ? 'Update' : 'Create' }}</button>
        <a href="{{ route('admin.coverage.index') }}" class="rounded-lg border border-slate-300 px-5 py-2 text-sm hover:bg-slate-50">Cancel</a>
    </div>
</form>
@endsection
