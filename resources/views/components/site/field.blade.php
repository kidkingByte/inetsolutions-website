@props(['name', 'label', 'type' => 'text', 'required' => false, 'value' => null, 'hint' => null])
@php
    $id = $attributes->get('id', 'f-'.$name);
    $current = old($name, $value);
    $hasError = $errors->has($name);
    $describedBy = $hasError ? "{$id}-error" : ($hint ? "{$id}-hint" : null);
@endphp
<div {{ $attributes->only('class') }}>
    <label for="{{ $id }}" class="field-label">{{ $label }}@if($required)<span class="text-brand-600"> *</span>@endif</label>

    @if($type === 'textarea')
        <textarea id="{{ $id }}" name="{{ $name }}" @required($required) @if($describedBy) aria-describedby="{{ $describedBy }}" @endif @if($hasError) aria-invalid="true" @endif
                  {{ $attributes->except(['class', 'id'])->merge(['rows' => 5, 'class' => 'field']) }}>{{ $current }}</textarea>
    @elseif($type === 'select')
        <select id="{{ $id }}" name="{{ $name }}" @required($required) @if($describedBy) aria-describedby="{{ $describedBy }}" @endif @if($hasError) aria-invalid="true" @endif
                {{ $attributes->except(['class', 'id'])->merge(['class' => 'field']) }}>
            {{ $slot }}
        </select>
    @elseif($type === 'file')
        <input id="{{ $id }}" name="{{ $name }}" type="file" @if($describedBy) aria-describedby="{{ $describedBy }}" @endif
               {{ $attributes->except(['class', 'id'])->merge(['class' => 'field-file']) }}>
    @else
        <input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}" value="{{ $current }}" @required($required) @if($describedBy) aria-describedby="{{ $describedBy }}" @endif @if($hasError) aria-invalid="true" @endif
               {{ $attributes->except(['class', 'id'])->merge(['class' => 'field']) }}>
    @endif

    @if($hasError)
        <p id="{{ $id }}-error" class="field-error">{{ $errors->first($name) }}</p>
    @elseif($hint)
        <p id="{{ $id }}-hint" class="mt-1.5 text-xs text-slate-500">{{ $hint }}</p>
    @endif
</div>
