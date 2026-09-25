@extends('layouts.admin')

@section('title', 'Settings')
@section('heading', 'Website Settings')

@section('content')
<form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
    @csrf
    @method('PUT')

    @foreach ($settings as $group => $items)
        <div class="bg-white rounded-xl border border-slate-200">
            <div class="px-5 py-4 border-b border-slate-100">
                <h2 class="font-semibold capitalize">{{ str_replace('_',' ',$group) }}</h2>
            </div>
            <div class="p-5 grid sm:grid-cols-2 gap-4">
                @foreach ($items as $setting)
                    <div class="{{ in_array($setting->type, ['textarea','json']) ? 'sm:col-span-2' : '' }}">
                        <label class="block text-sm font-medium">{{ $setting->label ?: $setting->key }}</label>
                        @php $value = old('settings.' . $setting->key, $setting->value); @endphp
                        @if (in_array($setting->type, ['textarea','json']))
                            <textarea name="settings[{{ $setting->key }}]" rows="{{ $setting->type === 'json' ? 5 : 3 }}"
                                      class="mt-1 w-full rounded-lg border-slate-300 {{ $setting->type === 'json' ? 'font-mono text-xs' : '' }}">{{ $value }}</textarea>
                        @else
                            <input name="settings[{{ $setting->key }}]" value="{{ $value }}"
                                   type="{{ $setting->type === 'url' ? 'url' : ($setting->type === 'email' ? 'email' : 'text') }}"
                                   class="mt-1 w-full rounded-lg border-slate-300">
                        @endif
                        <p class="text-xs text-slate-400 mt-1">{{ $setting->key }}@if($setting->type === 'json') · JSON @endif</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <div class="flex gap-3">
        <button class="rounded-lg bg-brand-700 px-5 py-2 text-sm font-semibold text-white hover:bg-brand-800">Save Settings</button>
    </div>
</form>
@endsection
