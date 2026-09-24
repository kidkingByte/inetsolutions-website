<x-site-layout>
    <x-slot name="title">Speed Test — {{ site('company_name') }}</x-slot>
    <x-site.page-banner title="Test Your Internet Speed" subtitle="Check your current internet connection performance." />

    <section class="py-20 bg-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8"
             x-data="speedTest()"
             x-init="">
            <div class="grid grid-cols-3 gap-4 text-center">
                <div class="rounded-2xl bg-brand-light p-6">
                    <p class="text-xs uppercase tracking-widest text-slate-500">Download</p>
                    <p class="mt-2 text-3xl font-extrabold text-brand-700" x-text="download.toFixed(1)">0.0</p>
                    <p class="text-xs text-slate-500">Mbps</p>
                </div>
                <div class="rounded-2xl bg-brand-light p-6">
                    <p class="text-xs uppercase tracking-widest text-slate-500">Upload</p>
                    <p class="mt-2 text-3xl font-extrabold text-brand-700" x-text="upload.toFixed(1)">0.0</p>
                    <p class="text-xs text-slate-500">Mbps</p>
                </div>
                <div class="rounded-2xl bg-brand-light p-6">
                    <p class="text-xs uppercase tracking-widest text-slate-500">Ping</p>
                    <p class="mt-2 text-3xl font-extrabold text-brand-700" x-text="ping">0</p>
                    <p class="text-xs text-slate-500">ms</p>
                </div>
            </div>

            <div class="mt-8 text-center">
                <button @click="run" :disabled="running" class="rounded-lg bg-brand-600 px-8 py-3 text-sm font-semibold text-white hover:bg-brand-700 disabled:opacity-50">
                    <span x-show="!running">Start Speed Test</span>
                    <span x-show="running" x-cloak>Testing…</span>
                </button>
                <p class="mt-3 text-xs text-slate-500">Results are approximate and depend on your network.</p>
            </div>
        </div>
    </section>

    @include('site.partials.cta-banner')

    @push('scripts')
    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('speedTest', () => ({
            download: 0, upload: 0, ping: 0, running: false,
            async run() {
                this.running = true;
                this.download = this.upload = 0;
                try {
                    // Ping
                    const t0 = performance.now();
                    await fetch('https://speed.cloudflare.com/__down?bytes=0', { cache: 'no-store' });
                    this.ping = Math.max(1, Math.round((performance.now() - t0)));
                    // Download (25 MB)
                    const bytes = 25 * 1000 * 1000;
                    const dStart = performance.now();
                    const res = await fetch('https://speed.cloudflare.com/__down?bytes=' + bytes, { cache: 'no-store' });
                    await res.blob();
                    const dSec = (performance.now() - dStart) / 1000;
                    this.download = (bytes * 8) / (dSec * 1e6);
                    // Upload (5 MB)
                    const upBytes = 5 * 1000 * 1000;
                    const payload = new Uint8Array(upBytes);
                    const uStart = performance.now();
                    await fetch('https://speed.cloudflare.com/__up', { method: 'POST', body: payload, cache: 'no-store' });
                    const uSec = (performance.now() - uStart) / 1000;
                    this.upload = (upBytes * 8) / (uSec * 1e6);
                } catch (e) {
                    console.error(e);
                } finally {
                    this.running = false;
                }
            }
        }));
    });
    </script>
    @endpush
</x-site-layout>
