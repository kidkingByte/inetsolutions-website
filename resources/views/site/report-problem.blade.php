@php
    $problemTypes = ['No Internet', 'Slow Internet', 'Router Problem', 'Wi-Fi Problem', 'Payment Problem', 'Package Problem', 'Installation', 'Other'];
@endphp
<x-site-layout>
    <x-slot name="title">Report a Problem — {{ site('company_name') }}</x-slot>

    <x-site.page-banner eyebrow="Support" title="Report a Problem" subtitle="Tell us what's happening and our support team will assist you." />

    <section class="section">
        <div class="container-x grid gap-12 lg:grid-cols-12">
            <div class="lg:col-span-8">
                @include('site.partials.alerts')
                <form method="POST" action="{{ route('support.report.store') }}" enctype="multipart/form-data" class="card space-y-5">
                    @csrf
                    <div class="grid gap-5 sm:grid-cols-2">
                        <x-site.field name="full_name" label="Customer Name" required autocomplete="name" />
                        <x-site.field name="customer_id" label="Customer ID" hint="Found on your invoice or in the iNet app" />
                        <x-site.field name="phone" label="Phone Number" type="tel" required autocomplete="tel" />
                        <x-site.field name="problem_type" label="Problem Type" type="select" required>
                            <option value="">Select…</option>
                            @foreach($problemTypes as $pt)
                                <option value="{{ $pt }}" @selected(old('problem_type', request('problem_type')) === $pt)>{{ $pt }}</option>
                            @endforeach
                        </x-site.field>
                    </div>
                    <x-site.field name="address" label="Location" placeholder="Area, street or landmark" />
                    <x-site.field name="message" label="Description" type="textarea" required rows="6" :value="request('message')" placeholder="What happened, since when, and what have you tried?" />
                    <x-site.field name="attachment" label="Attachment (optional)" type="file" accept=".jpg,.jpeg,.png,.pdf" hint="JPG, PNG or PDF, up to 5 MB" />
                    <button type="submit" class="btn-primary w-full py-3.5">Submit Support Request</button>
                </form>
            </div>

            <aside class="space-y-5 lg:col-span-4">
                <div class="card reveal">
                    <h2 class="text-lg font-bold">Before you submit</h2>
                    <ul class="mt-4 space-y-3 text-sm text-slate-500">
                        <li class="flex gap-3"><x-site.icon name="check" class="mt-0.5 h-4 w-4 shrink-0 text-brand-600" /> Restart your router and wait two minutes.</li>
                        <li class="flex gap-3"><x-site.icon name="check" class="mt-0.5 h-4 w-4 shrink-0 text-brand-600" /> Check that all cables are firmly connected.</li>
                        <li class="flex gap-3"><x-site.icon name="check" class="mt-0.5 h-4 w-4 shrink-0 text-brand-600" /> Look for known issues on the network status page.</li>
                    </ul>
                    <a href="{{ route('network-status') }}" class="link-arrow mt-5">View network status <x-site.icon name="arrow-right" class="h-4 w-4" /></a>
                </div>
                <div class="card reveal">
                    <p class="font-semibold text-ink">Need help right now?</p>
                    <div class="mt-4 flex flex-wrap gap-3">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', site('whatsapp', '')) }}?text={{ rawurlencode('I need technical support.') }}" target="_blank" rel="noopener" class="btn-whatsapp"><x-site.icon name="whatsapp" class="h-4 w-4" /> WhatsApp</a>
                        <a href="tel:{{ preg_replace('/\s+/', '', site('phone')) }}" class="btn-secondary"><x-site.icon name="phone" class="h-4 w-4" /> Call</a>
                    </div>
                </div>
            </aside>
        </div>
    </section>
</x-site-layout>
