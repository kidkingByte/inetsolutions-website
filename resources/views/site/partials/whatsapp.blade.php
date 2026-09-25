@php
    $wa = preg_replace('/[^0-9]/', '', site('whatsapp', ''));
    $quickMessages = [
        'Hello INET Solutions, I would like to know more about your internet services.',
        'I want to subscribe to an internet package.',
        'I want to check coverage in my area.',
        'I need technical support.',
        'I want to speak with the sales team.',
    ];
@endphp
@if($wa)
<div x-data="{ open: false }" @keydown.escape.window="open = false" class="fixed bottom-5 right-5 z-40 flex flex-col items-end gap-3">
    <div x-show="open" x-cloak x-transition.origin.bottom.right @click.outside="open = false"
         class="glass w-[min(20rem,calc(100vw-2.5rem))] overflow-hidden">
        <div class="flex items-center gap-3 border-b border-slate-200 bg-[#25D366]/10 px-5 py-4">
            <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-[#25D366] text-ink"><x-site.icon name="whatsapp" class="h-5 w-5" /></span>
            <div>
                <p class="text-sm font-semibold text-ink">Chat with INET</p>
                <p class="text-xs text-slate-500">Choose a message to start</p>
            </div>
        </div>
        <ul class="p-2">
            @foreach($quickMessages as $message)
                <li>
                    <a href="https://wa.me/{{ $wa }}?text={{ rawurlencode($message) }}" target="_blank" rel="noopener"
                       class="flex items-center justify-between gap-3 rounded-xl px-3 py-2.5 text-sm text-slate-700 transition hover:bg-slate-50 hover:text-brand-700">
                        {{ $message }}
                        <x-site.icon name="arrow-right" class="h-4 w-4 shrink-0 text-[#25D366]" />
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    <button type="button" @click="open = !open" :aria-expanded="open" aria-label="Chat with us on WhatsApp"
            class="relative inline-flex h-14 w-14 items-center justify-center rounded-full bg-[#25D366] text-ink shadow-xl shadow-[#25D366]/30 transition hover:scale-105">
        <span class="absolute inset-0 animate-pulse-ring rounded-full bg-[#25D366]" x-show="!open"></span>
        <x-site.icon name="whatsapp" class="relative h-7 w-7" x-show="!open" />
        <x-site.icon name="x" class="relative h-6 w-6" x-show="open" x-cloak />
    </button>
</div>
@endif
