@php
    // Dial: 270° arc (r=120) with tick labels on the same non-linear scale as resources/js/speed-test.js.
    $ticks = [0, 1, 5, 10, 20, 30, 50, 75, 100, 250, 500, 1000];
    $arc = round(2 * M_PI * 120 * 0.75, 2);
    $circumference = round(2 * M_PI * 120, 2);
    $labels = collect($ticks)->map(function ($tick, $i) use ($ticks) {
        $angle = deg2rad(-135 + $i / (count($ticks) - 1) * 270);
        return ['x' => round(150 + 92 * sin($angle), 1), 'y' => round(150 - 92 * cos($angle), 1), 'text' => $tick >= 1000 ? '1G' : $tick];
    });
    $metrics = [
        ['download', 'Download', 'Mbps', 'M12 4.5v15m0 0 6.75-6.75M12 19.5l-6.75-6.75'],
        ['upload', 'Upload', 'Mbps', 'M12 19.5v-15m0 0-6.75 6.75M12 4.5l6.75 6.75'],
        ['ping', 'Ping', 'ms', null],
        ['jitter', 'Jitter', 'ms', null],
    ];
@endphp
<x-site-layout>
    <x-slot name="title">Speed Test — {{ site('company_name') }}</x-slot>
    <x-slot name="meta_description">Test your internet speed: download, upload, ping and jitter — and see what your connection can handle.</x-slot>

    <x-site.page-banner eyebrow="Speed test" title="Test Your Internet Speed" subtitle="Measure your download, upload, ping and jitter in about 20 seconds — and see what your connection can handle." />

    <section class="section !pt-12 sm:!pt-16" x-data="speedTest" data-report-url="{{ route('support.report') }}">
        <div class="container-x grid gap-8 lg:grid-cols-12">
            {{-- Dial --}}
            <div class="glass relative overflow-hidden p-6 sm:p-10 lg:col-span-7">
                <div class="pointer-events-none absolute -top-32 left-1/2 h-72 w-72 -translate-x-1/2 rounded-full bg-brand-100 blur-3xl transition-opacity duration-700" :class="running ? 'opacity-100' : 'opacity-40'"></div>

                <div class="relative flex items-center justify-between gap-4 text-xs text-slate-500">
                    <p class="flex items-center gap-2">
                        <span class="relative flex h-2 w-2">
                            <span x-show="running" class="absolute inline-flex h-full w-full animate-ping rounded-full bg-brand-500 opacity-75"></span>
                            <span class="relative inline-flex h-2 w-2 rounded-full" :class="phase === 'error' ? 'bg-red-400' : (running ? 'bg-brand-500' : (phase === 'done' ? 'bg-emerald-400' : 'bg-slate-500'))"></span>
                        </span>
                        <span class="font-semibold uppercase tracking-[0.2em]" x-text="phaseLabel">Ready</span>
                    </p>
                    <p class="truncate" x-show="meta" x-cloak>Server <span class="text-slate-700" x-text="meta?.server"></span></p>
                </div>

                <div class="relative mx-auto mt-4 aspect-square w-full max-w-[22rem]">
                    <svg viewBox="0 0 300 300" class="h-full w-full" role="img" :aria-label="`${phaseLabel}: ${dialValue.toFixed(1)} ${unit}`">
                        <defs>
                            <linearGradient id="dial-gradient" x1="0" y1="1" x2="1" y2="0">
                                <stop offset="0" stop-color="#1E57D6" />
                                <stop offset=".55" stop-color="#463CA5" />
                                <stop offset="1" stop-color="#E2202C" />
                            </linearGradient>
                            <filter id="dial-glow" x="-20%" y="-20%" width="140%" height="140%"><feGaussianBlur stdDeviation="4" result="b" /><feMerge><feMergeNode in="b" /><feMergeNode in="SourceGraphic" /></feMerge></filter>
                        </defs>
                        <circle cx="150" cy="150" r="120" fill="none" stroke="#EEF1F6" stroke-width="14" stroke-linecap="round"
                                stroke-dasharray="{{ $arc }} {{ $circumference }}" transform="rotate(135 150 150)" />
                        <circle cx="150" cy="150" r="120" fill="none" stroke="url(#dial-gradient)" stroke-width="14" stroke-linecap="round"
                                :stroke-dasharray="`${Math.max(0.001, {{ $arc }} * (phase === 'ping' ? 0 : dial))} {{ $circumference }}`" transform="rotate(135 150 150)" />
                        @foreach($labels as $l)
                            <text x="{{ $l['x'] }}" y="{{ $l['y'] }}" text-anchor="middle" dominant-baseline="middle" class="fill-slate-500 text-[10px] font-medium">{{ $l['text'] }}</text>
                        @endforeach
                        <g :style="`transform: rotate(${phase === 'ping' ? -135 : needle}deg); transform-origin: 150px 150px`">
                            <line x1="150" y1="150" x2="150" y2="46" stroke="#0F1115" stroke-width="3" stroke-linecap="round" />
                        </g>
                        <circle cx="150" cy="150" r="9" class="fill-white" stroke="#0F1115" stroke-width="3" />
                    </svg>

                    <div class="pointer-events-none absolute inset-x-0 bottom-[8%] text-center">
                        <p class="text-5xl font-extrabold tabular-nums text-ink sm:text-6xl" x-text="phase === 'ping' ? (ping ?? 0) : fmt(live)">0.0</p>
                        <p class="text-sm text-slate-500" x-text="unit">Mbps</p>
                    </div>
                </div>

                <div class="relative mt-6 h-1.5 overflow-hidden rounded-full bg-slate-100" role="progressbar" aria-label="Test progress" aria-valuemin="0" aria-valuemax="100" :aria-valuenow="Math.round(progress * 100)">
                    <div class="h-full rounded-full bg-signal transition-[width] duration-200" :style="`width: ${progress * 100}%`"></div>
                </div>

                <div class="relative mt-8 flex flex-wrap items-center justify-center gap-3">
                    <button type="button" x-show="! running" @click="run()" class="btn-primary px-10 py-4 text-base">
                        <x-site.icon name="bolt" class="h-5 w-5" />
                        <span x-text="phase === 'done' || phase === 'error' ? 'Test Again' : 'Start Speed Test'">Start Speed Test</span>
                    </button>
                    <button type="button" x-show="running" x-cloak @click="cancel()" class="btn-secondary px-8 py-4">
                        <x-site.icon name="x" class="h-5 w-5" /> Cancel
                    </button>
                </div>
                <p x-show="error" x-cloak x-text="error" role="alert" class="relative mt-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-center text-sm text-red-700"></p>
                <p class="relative mt-5 text-center text-xs text-slate-500" x-show="meta" x-cloak>
                    Your provider: <span class="text-slate-600" x-text="meta?.isp"></span> · <span x-text="meta?.city"></span>
                </p>
            </div>

            {{-- Metrics + verdict --}}
            <div class="space-y-5 lg:col-span-5">
                <div class="grid grid-cols-2 gap-4">
                    @foreach($metrics as [$key, $label, $unit, $arrow])
                        <div class="rounded-2xl border p-5 transition duration-300"
                             :class="phase === '{{ $key === 'jitter' ? 'ping' : $key }}' ? 'border-brand-200 bg-brand-50' : 'border-slate-200 bg-white'">
                            <p class="flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                                @if($arrow)
                                    <svg class="h-4 w-4 {{ $key === 'download' ? 'text-brand-600' : 'text-signal-violet' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $arrow }}" /></svg>
                                @else
                                    <x-site.icon name="signal" class="h-4 w-4 text-slate-500" />
                                @endif
                                {{ $label }}
                            </p>
                            <p class="mt-3 text-3xl font-extrabold tabular-nums text-ink">
                                <span x-text="{{ in_array($key, ['ping', 'jitter']) ? "{$key} ?? '—'" : "fmt({$key})" }}">—</span>
                                <span class="text-sm font-medium text-slate-500">{{ $unit }}</span>
                            </p>
                        </div>
                    @endforeach
                </div>

                <div class="card" x-show="phase === 'done'" x-cloak x-transition.opacity>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Your connection</p>
                    <p class="mt-1 text-2xl font-extrabold" :class="rating[1]" x-text="rating[0]"></p>
                    <ul class="mt-5 space-y-2.5 text-sm">
                        <template x-for="[label, ok] in activities" :key="label">
                            <li class="flex items-center gap-3" :class="ok ? 'text-slate-700' : 'text-slate-500'">
                                <span class="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full" :class="ok ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-50 text-slate-500'">
                                    <svg x-show="ok" class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                                    <svg x-show="! ok" class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" d="M5 12h14" /></svg>
                                </span>
                                <span x-text="label"></span>
                            </li>
                        </template>
                    </ul>
                    <div class="mt-6 flex flex-wrap gap-3 border-t border-slate-200 pt-6">
                        <a :href="reportUrl" class="btn-secondary px-5 py-2.5">Slower than expected? Report it</a>
                        <a href="{{ route('packages') }}" class="link-arrow">Compare packages <x-site.icon name="arrow-right" class="h-4 w-4" /></a>
                    </div>
                </div>

                <div class="card" x-show="phase !== 'done'">
                    <h2 class="text-base font-bold">For the most accurate result</h2>
                    <ul class="mt-4 space-y-3 text-sm text-slate-500">
                        <li class="flex gap-3"><x-site.icon name="check" class="mt-0.5 h-4 w-4 shrink-0 text-brand-600" /> Connect with a cable, or stay close to your Wi-Fi router.</li>
                        <li class="flex gap-3"><x-site.icon name="check" class="mt-0.5 h-4 w-4 shrink-0 text-brand-600" /> Pause downloads, streaming and updates on other devices.</li>
                        <li class="flex gap-3"><x-site.icon name="check" class="mt-0.5 h-4 w-4 shrink-0 text-brand-600" /> Run the test two or three times at different times of day.</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- History (stored only in this browser) --}}
        <div class="container-x mt-8" x-show="history.length" x-cloak>
            <div class="card !p-0 overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4">
                    <h2 class="text-base font-bold">Your recent tests</h2>
                    <button type="button" @click="clearHistory()" class="text-xs font-semibold text-slate-500 hover:text-brand-700">Clear</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[32rem] text-sm">
                        <thead class="border-y border-slate-200 text-left text-xs uppercase tracking-wider text-slate-500">
                            <tr><th class="px-6 py-3 font-semibold">Date</th><th class="px-6 py-3 font-semibold">Download</th><th class="px-6 py-3 font-semibold">Upload</th><th class="px-6 py-3 font-semibold">Ping</th><th class="px-6 py-3 font-semibold">Jitter</th></tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <template x-for="h in history" :key="h.at">
                                <tr class="text-slate-600">
                                    <td class="px-6 py-3 text-slate-500" x-text="new Date(h.at).toLocaleString([], { dateStyle: 'medium', timeStyle: 'short' })"></td>
                                    <td class="px-6 py-3 font-semibold tabular-nums text-ink" x-text="fmt(h.download) + ' Mbps'"></td>
                                    <td class="px-6 py-3 tabular-nums" x-text="fmt(h.upload) + ' Mbps'"></td>
                                    <td class="px-6 py-3 tabular-nums" x-text="h.ping + ' ms'"></td>
                                    <td class="px-6 py-3 tabular-nums" x-text="h.jitter + ' ms'"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
            <p class="mt-3 text-xs text-slate-500">Results are saved only in this browser. The test uses Cloudflare's global speed-test network; results depend on your device, Wi-Fi and network conditions.</p>
        </div>
    </section>

    @include('site.partials.cta-banner')
</x-site-layout>
