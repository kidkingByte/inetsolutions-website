@php
    $services = ['home' => 'Home Internet', 'business' => 'Business Internet', 'enterprise' => 'Enterprise Internet', 'wifi' => 'Wi-Fi Solution'];
    $servedRegions = array_keys(\App\Models\CoverageArea::locationTree());
@endphp
<x-site-layout>
    <x-slot name="title">Check Coverage — {{ site('company_name') }}</x-slot>

    <x-site.page-banner eyebrow="Coverage" title="Is INET Available in Your Area?" subtitle="INET serves Zanzibar — across Unguja and Pemba. Choose your location to check availability where you are.">
        @if($servedRegions)
            <div class="mt-10 flex flex-wrap items-center gap-2">
                <span class="mr-2 text-sm text-slate-600">Where we operate:</span>
                @foreach($servedRegions as $r)
                    <span class="inline-flex items-center gap-2 rounded-full border border-brand-200 bg-brand-50 px-3 py-1 text-sm text-brand-600"><span class="h-1.5 w-1.5 rounded-full bg-brand-500"></span>{{ \App\Models\CoverageArea::regionLabel($r) }}</span>
                @endforeach
            </div>
        @endif
    </x-site.page-banner>

    <section class="section">
        <div class="container-x grid gap-12 lg:grid-cols-12">
            <div class="lg:col-span-7"
                 x-data="{
                    loading: false, error: '',
                    async check(form) {
                        this.loading = true; this.error = '';
                        try {
                            const res = await fetch(form.action, {
                                method: 'POST',
                                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                                body: new FormData(form),
                            });
                            if (res.status === 422) { form.submit(); return; }
                            if (res.status === 429) { this.error = 'Too many checks — please wait a minute and try again.'; return; }
                            if (! res.ok) throw new Error();
                            this.$refs.result.innerHTML = (await res.json()).html;
                            this.$refs.result.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            this.$refs.result.firstElementChild?.focus({ preventScroll: true });
                        } catch (e) {
                            form.submit();
                        } finally {
                            this.loading = false;
                        }
                    },
                 }">
                <div x-ref="result" aria-live="polite" class="mb-8 empty:hidden">@isset($result)@include('site.partials.coverage-result', ['result' => $result, 'input' => $input])@endisset</div>
                <p x-show="error" x-cloak x-text="error" class="mb-6 rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-700"></p>

                @include('site.partials.alerts')

                <form id="check" method="POST" action="{{ route('coverage.check') }}" @submit.prevent="check($el)" class="card scroll-mt-32 space-y-8">
                    @csrf
                    <fieldset>
                        <legend class="flex items-center gap-3 text-sm font-semibold uppercase tracking-[0.2em] text-brand-600">
                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-brand-50 text-xs">1</span> Your location
                        </legend>
                        {{-- ?region= is set by the map's "Check this area" links --}}
                        <x-site.location-picker class="mt-5" id-prefix="cov" :region="request('region')" />
                        <div class="mt-5">
                            <x-site.field name="street" label="Street (optional)" placeholder="Street or nearby landmark" />
                        </div>
                    </fieldset>

                    <fieldset class="border-t border-slate-200 pt-8">
                        <legend class="flex items-center gap-3 text-sm font-semibold uppercase tracking-[0.2em] text-brand-600">
                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-brand-50 text-xs">2</span> Service &amp; contact <span class="normal-case tracking-normal text-slate-500">(optional)</span>
                        </legend>
                        <div class="mt-5 grid gap-5 sm:grid-cols-2">
                            <x-site.field name="service_required" label="Service Required" type="select" class="sm:col-span-2">
                                <option value="">Any service</option>
                                @foreach($services as $value => $label)
                                    <option value="{{ $value }}" @selected(old('service_required') === $value)>{{ $label }}</option>
                                @endforeach
                            </x-site.field>
                            <x-site.field name="full_name" label="Full Name" autocomplete="name" />
                            <x-site.field name="phone" label="Phone Number" type="tel" autocomplete="tel" hint="So our team can confirm installation" />
                        </div>
                    </fieldset>

                    <button type="submit" class="btn-primary w-full py-4 text-base" :disabled="loading">
                        <svg x-show="loading" x-cloak class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-opacity=".25" stroke-width="3"/><path d="M22 12a10 10 0 0 0-10-10" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>
                        <x-site.icon name="search" class="h-5 w-5" x-show="! loading" />
                        <span x-text="loading ? 'Checking…' : 'Check Availability'">Check Availability</span>
                    </button>
                </form>
            </div>

            <aside class="space-y-5 lg:col-span-5">
                {{-- Coverage map (Leaflet, loaded only on this page) --}}
                @if($mapPoints)
                    <div class="card !p-0 overflow-hidden">
                        <div class="flex items-center justify-between gap-3 px-5 pb-3 pt-5">
                            <h2 class="text-lg font-bold">Coverage map</h2>
                            <span class="text-xs text-slate-500">Tap an area for details</span>
                        </div>
                        <div data-coverage-map data-points="{{ json_encode($mapPoints) }}" data-check-url="{{ route('coverage') }}"
                             class="relative z-0 h-80 bg-brand-light sm:h-96" role="region" aria-label="Map of the areas INET serves">
                            <noscript><p class="p-5 text-sm text-slate-500">Turn on JavaScript to see the map.</p></noscript>
                        </div>
                        <div class="flex flex-wrap items-center gap-x-5 gap-y-2 border-t border-slate-100 px-5 py-3 text-xs text-slate-600">
                            <span class="flex items-center gap-2"><span class="h-3 w-3 rounded-full border-2 border-white bg-brand-600 ring-1 ring-brand-600"></span> Available</span>
                            <span class="flex items-center gap-2"><span class="h-3 w-3 rounded-full border-2 border-white bg-amber-500 ring-1 ring-amber-500"></span> Coming soon</span>
                        </div>
                        {{-- Text version of the map for screen readers and no-JS visitors --}}
                        <ul class="sr-only">
                            @foreach($mapPoints as $point)
                                <li>{{ $point['title'] }}: {{ $point['status'] === 'available' ? 'available' : 'coming soon' }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card reveal">
                    <span class="icon-tile"><x-site.icon name="map-pin" class="h-6 w-6" /></span>
                    <h2 class="mt-5 text-lg font-bold">How the check works</h2>
                    <ol class="mt-5 space-y-4 text-sm text-slate-500">
                        @foreach(['Choose your region, then your district and ward.', 'Can\'t find your area? Pick "Not listed" and type it in.', 'See your result instantly — then get connected or ask to be notified.'] as $i => $step)
                            <li class="flex gap-3"><span class="inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full border border-slate-200 text-xs text-slate-600">{{ $i + 1 }}</span> {{ $step }}</li>
                        @endforeach
                    </ol>
                </div>
                <div class="card reveal">
                    <p class="font-semibold text-ink">Prefer to ask directly?</p>
                    <p class="mt-2 text-sm text-slate-500">Our team can confirm coverage at your exact location.</p>
                    <div class="mt-5 flex flex-wrap gap-3">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', site('whatsapp', '')) }}?text={{ rawurlencode('I want to check coverage in my area.') }}" target="_blank" rel="noopener" class="btn-whatsapp"><x-site.icon name="whatsapp" class="h-4 w-4" /> WhatsApp</a>
                        <a href="tel:{{ preg_replace('/\s+/', '', site('phone')) }}" class="btn-secondary"><x-site.icon name="phone" class="h-4 w-4" /> Call us</a>
                    </div>
                </div>
            </aside>
        </div>
    </section>
</x-site-layout>
