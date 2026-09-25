@if(site('app_store_url'))
    <a href="{{ site('app_store_url') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-5 py-2.5 text-ink transition hover:border-slate-300 hover:bg-slate-50">
        <x-site.icon name="apple" class="h-7 w-7" />
        <span class="text-left leading-tight"><span class="block text-[10px] uppercase tracking-wider text-slate-500">Download on the</span><span class="block text-base font-semibold">App Store</span></span>
    </a>
@endif
@if(site('app_play_url'))
    <a href="{{ site('app_play_url') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-5 py-2.5 text-ink transition hover:border-slate-300 hover:bg-slate-50">
        <x-site.icon name="google-play" class="h-6 w-6" />
        <span class="text-left leading-tight"><span class="block text-[10px] uppercase tracking-wider text-slate-500">Get it on</span><span class="block text-base font-semibold">Google Play</span></span>
    </a>
@endif
