@props(['screen', 'alt', 'eager' => false])
{{-- A real iNet App screenshot (public/images/app/{screen}.webp|jpg) in a simple phone frame.
     Width/height reserve the 9:20 space so nothing shifts while the image loads. --}}
<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-[2.2rem] border-[7px] border-ink bg-ink shadow-2xl shadow-slate-900/25']) }}>
    <picture>
        <source srcset="{{ asset("images/app/{$screen}.webp") }}" type="image/webp">
        <img src="{{ asset("images/app/{$screen}.jpg") }}" alt="{{ $alt }}" width="576" height="1280"
             class="block h-auto w-full rounded-[1.7rem]" loading="{{ $eager ? 'eager' : 'lazy' }}" decoding="async">
    </picture>
</div>
