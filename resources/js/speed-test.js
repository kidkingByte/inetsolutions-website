// Browser speed test against Cloudflare's public speed endpoints (the same ones speed.cloudflare.com uses).
//
// Method, following fast.com / speed.cloudflare.com practice:
//  - Latency: many tiny requests; server processing time (Server-Timing) is subtracted; median = ping,
//    mean difference between consecutive samples = jitter.
//  - Download / upload: time-boxed, several parallel streams, chunk size grows while chunks finish
//    quickly. The first seconds (TCP ramp-up) are excluded from the final figure.

const BASE = 'https://speed.cloudflare.com';
const PING_SAMPLES = 20;
const DOWNLOAD = { duration: 10000, warmup: 2000, streams: 4, start: 256e3, max: 50e6 };
const UPLOAD = { duration: 8000, warmup: 1500, streams: 3, start: 128e3, max: 16e6 };
const HISTORY_KEY = 'inet-speedtest-history';

// Dial scale: value → fraction of the arc. Non-linear so slow and fast lines both read well.
const TICKS = [0, 1, 5, 10, 20, 30, 50, 75, 100, 250, 500, 1000];

export function dialFraction(mbps) {
    if (mbps <= 0) return 0;
    for (let i = 1; i < TICKS.length; i++) {
        if (mbps <= TICKS[i]) {
            return (i - 1 + (mbps - TICKS[i - 1]) / (TICKS[i] - TICKS[i - 1])) / (TICKS.length - 1);
        }
    }
    return 1;
}

const now = () => performance.now();
const median = (xs) => {
    const s = [...xs].sort((a, b) => a - b);
    const m = Math.floor(s.length / 2);
    return s.length % 2 ? s[m] : (s[m - 1] + s[m]) / 2;
};

function serverTime(response) {
    const match = /dur=([\d.]+)/.exec(response.headers.get('server-timing') || '');
    return match ? parseFloat(match[1]) : 0;
}

/** Tracks bytes over time for one direction and derives live + final throughput. */
class Meter {
    constructor(config) {
        this.config = config;
        this.bytes = 0;
        this.samples = [];
        this.started = now();
    }
    add(n) {
        this.bytes += n;
        this.samples.push([now(), this.bytes]);
    }
    /** Mbps over the last second — drives the dial. */
    live() {
        const t = now();
        const recent = this.samples.filter(([at]) => t - at <= 1000);
        if (recent.length < 2) return 0;
        const [t0, b0] = recent[0];
        const [t1, b1] = recent[recent.length - 1];
        return t1 > t0 ? ((b1 - b0) * 8) / ((t1 - t0) / 1000) / 1e6 : 0;
    }
    /** Mbps after warm-up; falls back to the whole run on very slow links with few samples. */
    final() {
        const cutoff = this.started + this.config.warmup;
        const after = this.samples.filter(([at]) => at >= cutoff);
        const use = after.length >= 2 ? after : this.samples;
        if (use.length < 2) return 0;
        const [t0, b0] = use[0];
        const [t1, b1] = use[use.length - 1];
        return t1 > t0 ? ((b1 - b0) * 8) / ((t1 - t0) / 1000) / 1e6 : 0;
    }
    elapsed() {
        return now() - this.started;
    }
}

async function downloadStream(meter, signal, end) {
    let size = DOWNLOAD.start;
    while (now() < end && !signal.aborted) {
        const t0 = now();
        const response = await fetch(`${BASE}/__down?bytes=${Math.round(size)}`, { cache: 'no-store', signal });
        const reader = response.body.getReader();
        for (;;) {
            const { done, value } = await reader.read();
            if (done) break;
            meter.add(value.length);
        }
        if (now() - t0 < 1000) size = Math.min(size * 2, DOWNLOAD.max);
    }
}

function uploadStream(meter, signal, end, payload) {
    return new Promise((resolve, reject) => {
        let size = UPLOAD.start;
        const next = () => {
            if (now() >= end || signal.aborted) return resolve();
            const xhr = new XMLHttpRequest();
            const t0 = now();
            let sent = 0;
            const abort = () => xhr.abort();
            signal.addEventListener('abort', abort, { once: true });
            xhr.upload.onprogress = (e) => {
                meter.add(e.loaded - sent);
                sent = e.loaded;
            };
            xhr.onload = () => {
                signal.removeEventListener('abort', abort);
                if (now() - t0 < 1000) size = Math.min(size * 2, UPLOAD.max);
                next();
            };
            xhr.onabort = () => resolve();
            xhr.onerror = () => reject(new Error('upload failed'));
            xhr.open('POST', `${BASE}/__up`);
            xhr.send(payload.subarray(0, Math.round(size)));
        };
        next();
    });
}

export function speedTest() {
    return {
        phase: 'idle', // idle | ping | download | upload | done | error
        running: false,
        live: 0, // smoothed value shown on the dial
        target: 0,
        ping: null,
        jitter: null,
        download: null,
        upload: null,
        progress: 0,
        meta: null,
        error: '',
        finishedAt: null,
        history: [],
        controller: null,

        init() {
            try {
                this.history = JSON.parse(localStorage.getItem(HISTORY_KEY) || '[]');
            } catch {
                this.history = [];
            }
            fetch(`${BASE}/meta`, { cache: 'no-store' })
                .then((r) => r.json())
                .then((m) => (this.meta = { isp: m.asOrganization, city: [m.city, m.country].filter(Boolean).join(', '), server: m.colo ? `${m.colo.city} (${m.colo.iata})` : null }))
                .catch(() => {});
            const smooth = () => {
                this.live += (this.target - this.live) * 0.12;
                if (Math.abs(this.target - this.live) < 0.01) this.live = this.target;
                requestAnimationFrame(smooth);
            };
            requestAnimationFrame(smooth);
        },

        // Dial geometry: 270° arc, circumference fraction used by the SVG stroke.
        get dial() { return dialFraction(this.live); },
        get needle() { return -135 + this.dial * 270; },
        get phaseLabel() {
            return { idle: 'Ready', ping: 'Measuring ping', download: 'Download', upload: 'Upload', done: 'Complete', error: 'Stopped' }[this.phase];
        },
        get unit() { return this.phase === 'ping' ? 'ms' : 'Mbps'; },
        get dialValue() { return this.phase === 'ping' ? (this.ping ?? 0) : this.live; },

        get rating() {
            const d = this.download ?? 0;
            if (d >= 50) return ['Excellent', 'text-emerald-700'];
            if (d >= 25) return ['Very good', 'text-emerald-700'];
            if (d >= 10) return ['Good', 'text-brand-700'];
            if (d >= 3) return ['Fair', 'text-amber-700'];
            return ['Slow', 'text-red-700'];
        },
        get activities() {
            const d = this.download ?? 0, u = this.upload ?? 0, p = this.ping ?? 999, j = this.jitter ?? 999;
            return [
                ['Browsing, email & social media', d >= 1],
                ['HD video streaming', d >= 5],
                ['Video calls (Zoom, Meet, WhatsApp)', d >= 3 && u >= 1.5 && p < 150],
                ['4K streaming', d >= 25],
                ['Online gaming', d >= 3 && p < 60 && j < 20],
                ['Large uploads & cloud backup', u >= 10],
            ];
        },
        get reportUrl() {
            const summary = `Speed test ${new Date(this.finishedAt).toLocaleString()}: download ${this.fmt(this.download)} Mbps, upload ${this.fmt(this.upload)} Mbps, ping ${this.ping} ms, jitter ${this.jitter} ms` + (this.meta?.server ? `, server ${this.meta.server}` : '') + '.';
            const url = new URL(this.$root.dataset.reportUrl, window.location.origin);
            url.searchParams.set('problem_type', 'Slow Internet');
            url.searchParams.set('message', summary);
            return url.toString();
        },

        fmt(v) {
            if (v === null || v === undefined) return '—';
            return v >= 100 ? Math.round(v).toString() : v.toFixed(1);
        },

        async run() {
            if (this.running) return;
            Object.assign(this, { running: true, error: '', ping: null, jitter: null, download: null, upload: null, target: 0, progress: 0, finishedAt: null });
            this.controller = new AbortController();
            const { signal } = this.controller;

            try {
                await this.measureLatency(signal);
                this.download = await this.measureThroughput('download', DOWNLOAD, signal, 0.1, 0.55);
                this.target = 0;
                this.upload = await this.measureThroughput('upload', UPLOAD, signal, 0.55, 1);
                this.target = this.download;
                this.phase = 'done';
                this.progress = 1;
                this.finishedAt = Date.now();
                this.save();
            } catch (e) {
                this.target = 0;
                this.phase = signal.aborted ? 'idle' : 'error';
                if (!signal.aborted) this.error = navigator.onLine ? 'The test could not complete. Please check your connection and try again.' : 'You appear to be offline. Reconnect and try again.';
            } finally {
                this.running = false;
            }
        },

        cancel() {
            this.controller?.abort();
        },

        async measureLatency(signal) {
            this.phase = 'ping';
            const samples = [];
            for (let i = 0; i < PING_SAMPLES; i++) {
                const t0 = now();
                const response = await fetch(`${BASE}/__down?bytes=0`, { cache: 'no-store', signal });
                await response.arrayBuffer();
                if (i > 0) samples.push(Math.max(1, now() - t0 - serverTime(response))); // first request pays connection setup
                this.ping = Math.round(median(samples.length ? samples : [now() - t0]));
                this.progress = (0.1 * (i + 1)) / PING_SAMPLES;
            }
            const diffs = samples.slice(1).map((s, i) => Math.abs(s - samples[i]));
            this.jitter = Math.round(diffs.reduce((a, b) => a + b, 0) / (diffs.length || 1));
        },

        async measureThroughput(kind, config, signal, from, to) {
            this.phase = kind;
            const meter = new Meter(config);
            const end = now() + config.duration;
            const streamController = new AbortController();
            const stop = () => streamController.abort();
            signal.addEventListener('abort', stop, { once: true });

            const ticker = setInterval(() => {
                this.target = meter.live();
                this.progress = from + (to - from) * Math.min(1, meter.elapsed() / config.duration);
            }, 100);

            const payload = kind === 'upload' ? new Uint8Array(UPLOAD.max) : null;
            const streams = Array.from({ length: config.streams }, () =>
                kind === 'download'
                    ? downloadStream(meter, streamController.signal, end)
                    : uploadStream(meter, streamController.signal, end, payload),
            );
            const deadline = setTimeout(stop, config.duration);

            try {
                await Promise.all(streams.map((p) => p.catch((e) => { if (!streamController.signal.aborted) throw e; })));
            } finally {
                clearTimeout(deadline);
                clearInterval(ticker);
                signal.removeEventListener('abort', stop);
            }
            if (signal.aborted) throw new Error('cancelled');

            const result = meter.final();
            if (!result) throw new Error(`${kind} failed`);
            return result;
        },

        save() {
            const entry = { at: this.finishedAt, download: this.download, upload: this.upload, ping: this.ping, jitter: this.jitter };
            this.history = [entry, ...this.history].slice(0, 5);
            try {
                localStorage.setItem(HISTORY_KEY, JSON.stringify(this.history));
            } catch {
                // private mode / storage blocked: history just isn't kept
            }
        },

        clearHistory() {
            this.history = [];
            try {
                localStorage.removeItem(HISTORY_KEY);
            } catch {}
        },
    };
}
