<div class="reveal divide-y divide-slate-200 overflow-hidden rounded-3xl border border-slate-200 bg-white">
    @foreach($items as $faq)
        <div x-data="{ open: false }">
            <h3>
                <button type="button" @click="open = !open" :aria-expanded="open"
                        class="flex w-full items-center justify-between gap-6 px-6 py-5 text-left font-semibold text-ink transition hover:bg-slate-50">
                    {{ $faq->question }}
                    <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-slate-200 text-brand-600 transition" :class="open && 'rotate-45 border-brand-200'">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" d="M12 5v14M5 12h14"/></svg>
                    </span>
                </button>
            </h3>
            <div x-show="open" x-transition.opacity x-cloak class="px-6 pb-6 text-sm leading-relaxed text-slate-500">{{ $faq->answer }}</div>
        </div>
    @endforeach
</div>
