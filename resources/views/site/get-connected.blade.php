@php
    $services = ['home' => 'Home Internet', 'business' => 'Business Internet', 'enterprise' => 'Enterprise Internet', 'wifi' => 'Wi-Fi Solution', 'network' => 'Network Installation', 'managed' => 'Managed Connectivity'];
    $type = match ($service) {
        'business' => 'business_inquiry',
        'enterprise', 'managed' => 'enterprise_inquiry',
        default => 'connection_request',
    };
    $next = [
        ['Our sales team reviews your request', 'support'],
        ['We confirm coverage and your package', 'map-pin'],
        ['A technician installs your connection', 'wrench'],
        ['You manage everything in the iNet app', 'mobile'],
    ];
@endphp
<x-site-layout>
    <x-slot name="title">Get Connected — {{ site('company_name') }}</x-slot>

    <x-site.page-banner eyebrow="Get connected" title="Get Connected Today" subtitle="Ready to get reliable internet? Submit your connection request and our team will contact you." />

    <section class="section">
        <div class="container-x grid gap-12 lg:grid-cols-12">
            <div class="lg:col-span-8">
                @include('site.partials.alerts')

                <form method="POST" action="{{ route('get-connected.store') }}" class="card space-y-8">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">

                    <fieldset class="space-y-5">
                        <legend class="text-sm font-semibold uppercase tracking-[0.2em] text-brand-600">1 · Your details</legend>
                        <div class="grid gap-5 sm:grid-cols-2">
                            <x-site.field name="full_name" label="Full Name" required autocomplete="name" />
                            <x-site.field name="phone" label="Phone Number" type="tel" required autocomplete="tel" />
                            <x-site.field name="email" label="Email" type="email" autocomplete="email" class="sm:col-span-2" />
                        </div>
                    </fieldset>

                    <fieldset class="space-y-5 border-t border-slate-200 pt-8">
                        <legend class="text-sm font-semibold uppercase tracking-[0.2em] text-brand-600">2 · Installation location</legend>
                        <div class="grid gap-5 sm:grid-cols-2">
                            {{-- Prefilled when arriving from a coverage result --}}
                            <x-site.field name="region" label="Region" required placeholder="e.g. Mjini Magharibi" :value="request('region')" list="tz-regions" />
                            <x-site.field name="district" label="District" :value="request('district')" />
                            <x-site.field name="ward" label="Ward" :value="request('ward')" />
                            <datalist id="tz-regions">
                                @foreach(\App\Models\CoverageArea::TANZANIA_REGIONS as $r)<option value="{{ $r }}">@endforeach
                            </datalist>
                        </div>
                    </fieldset>

                    <fieldset class="space-y-5 border-t border-slate-200 pt-8">
                        <legend class="text-sm font-semibold uppercase tracking-[0.2em] text-brand-600">3 · Service</legend>
                        <div class="grid gap-5 sm:grid-cols-2">
                            <x-site.field name="service_required" label="Service Required" type="select" :value="$service">
                                <option value="">Select…</option>
                                @foreach($services as $value => $label)
                                    <option value="{{ $value }}" @selected(old('service_required', $service) === $value)>{{ $label }}</option>
                                @endforeach
                            </x-site.field>
                            <x-site.field name="preferred_package" label="Preferred Package" type="select">
                                <option value="">No preference</option>
                                @foreach($packages as $p)
                                    <option value="{{ $p->name }}" @selected(old('preferred_package', $package) === $p->name)>{{ $p->name }} ({{ ucfirst($p->category) }})</option>
                                @endforeach
                            </x-site.field>
                        </div>
                    </fieldset>

                    {{-- Optional extras stay folded so the form looks as short as it really is to complete --}}
                    @php $hasExtras = collect(['street', 'address', 'preferred_installation_date', 'message'])->contains(fn ($f) => filled(old($f)) || $errors->has($f)); @endphp
                    <div x-data="{ more: @js($hasExtras) }" class="border-t border-slate-200 pt-6">
                        <button type="button" @click="more = ! more" :aria-expanded="more" aria-controls="more-details"
                                class="flex min-h-11 w-full items-center justify-between rounded-xl text-left text-sm font-semibold text-ink hover:text-brand-700">
                            <span class="flex items-center gap-2"><x-site.icon name="document" class="h-5 w-5 text-brand-600" /> Add more details <span class="font-normal text-slate-500">(optional)</span></span>
                            <x-site.icon name="chevron-down" class="h-5 w-5 transition" ::class="more && 'rotate-180'" />
                        </button>
                        <div id="more-details" x-show="more" x-cloak x-transition.opacity class="mt-5 space-y-5">
                            <div class="grid gap-5 sm:grid-cols-2">
                                <x-site.field name="street" label="Street" />
                                <x-site.field name="preferred_installation_date" label="Preferred Installation Date" type="date" :min="now()->toDateString()" />
                                <x-site.field name="address" label="Installation Address" class="sm:col-span-2" placeholder="House number, building or landmark" />
                            </div>
                            <x-site.field name="message" label="Additional Message" type="textarea" rows="4" />
                        </div>
                    </div>

                    <button type="submit" class="btn-primary w-full py-4 text-base">Submit Connection Request <x-site.icon name="arrow-right" class="h-4 w-4" /></button>
                    <p class="text-center text-xs text-slate-500">By submitting you agree to our <a href="{{ route('legal', 'privacy-policy') }}" class="text-brand-600 hover:underline">Privacy Policy</a>.</p>
                </form>
            </div>

            <aside class="lg:col-span-4">
                <div class="space-y-5 lg:sticky lg:top-28">
                    <div class="card reveal">
                        <h2 class="text-lg font-bold">What happens next?</h2>
                        <ol class="mt-6 space-y-5">
                            @foreach($next as $i => [$text, $icon])
                                <li class="flex items-start gap-4">
                                    <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-brand-200 bg-brand-50 text-sm font-bold text-brand-600">{{ $i + 1 }}</span>
                                    <span class="pt-1.5 text-sm text-slate-600">{{ $text }}</span>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                    <div class="card reveal">
                        <p class="font-semibold text-ink">Not sure you're covered?</p>
                        <a href="{{ route('coverage') }}" class="link-arrow mt-3">Check coverage first <x-site.icon name="arrow-right" class="h-4 w-4" /></a>
                    </div>
                </div>
            </aside>
        </div>
    </section>
</x-site-layout>
