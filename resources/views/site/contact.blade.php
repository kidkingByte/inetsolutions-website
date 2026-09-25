@php
    $wa = preg_replace('/[^0-9]/', '', site('whatsapp', ''));
    $channels = [
        ['phone', 'Phone', site('phone'), 'tel:'.preg_replace('/\s+/', '', site('phone'))],
        ['mail', 'General enquiries', site('email'), 'mailto:'.site('email')],
        ['support', 'Support', site('support_email'), 'mailto:'.site('support_email')],
        ['building', 'Sales', site('sales_email'), 'mailto:'.site('sales_email')],
    ];
@endphp
<x-site-layout>
    <x-slot name="title">Contact Us — {{ site('company_name') }}</x-slot>

    <x-site.page-banner eyebrow="Contact" title="Get in Touch With INET" subtitle="Talk to us about connectivity for your home, business or organization." />

    <section class="section">
        <div class="container-x grid gap-12 lg:grid-cols-12">
            <aside class="space-y-5 lg:col-span-5">
                <div class="card reveal">
                    <h2 class="text-lg font-bold">{{ site('company_name') }}</h2>
                    <p class="mt-2 flex items-center gap-2 text-sm text-slate-500"><x-site.icon name="map-pin" class="h-4 w-4 text-brand-600" /> {{ site('address') }}</p>
                    <ul class="mt-6 divide-y divide-slate-200">
                        @foreach($channels as [$icon, $label, $value, $href])
                            @if($value)
                                <li>
                                    <a href="{{ $href }}" class="group flex items-center gap-4 py-4">
                                        <span class="icon-tile h-10 w-10"><x-site.icon :name="$icon" class="h-5 w-5" /></span>
                                        <span class="flex-1">
                                            <span class="block text-xs text-slate-500">{{ $label }}</span>
                                            <span class="block font-semibold text-ink group-hover:text-brand-700">{{ $value }}</span>
                                        </span>
                                        <x-site.icon name="arrow-right" class="h-4 w-4 text-slate-400 transition group-hover:translate-x-1 group-hover:text-brand-600" />
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                    @if($wa)
                        <a href="https://wa.me/{{ $wa }}?text={{ rawurlencode('Hello INET Solutions, I would like to know more about your internet services.') }}" target="_blank" rel="noopener" class="btn-whatsapp mt-4 w-full"><x-site.icon name="whatsapp" class="h-4 w-4" /> Chat With Us on WhatsApp</a>
                    @endif
                </div>
                <div class="card reveal">
                    <h2 class="flex items-center gap-2 text-lg font-bold"><x-site.icon name="clock" class="h-5 w-5 text-brand-600" /> Working Hours</h2>
                    <ul class="mt-4 space-y-2 text-sm">
                        @foreach(preg_split('/\r?\n/', trim(site('working_hours', ''))) as $line)
                            @php [$days, $hours] = array_pad(explode(':', $line, 2), 2, ''); @endphp
                            <li class="flex justify-between gap-4 border-b border-slate-100 pb-2 last:border-0"><span class="text-slate-500">{{ trim($days) }}</span><span class="text-right font-medium text-ink">{{ trim($hours) }}</span></li>
                        @endforeach
                    </ul>
                </div>
            </aside>

            <div class="lg:col-span-7">
                @include('site.partials.alerts')
                <form method="POST" action="{{ route('contact.store') }}" class="card space-y-5">
                    @csrf
                    <h2 class="text-2xl font-bold">Send us a message</h2>
                    <div class="grid gap-5 sm:grid-cols-2">
                        <x-site.field name="full_name" label="Full Name" required autocomplete="name" />
                        <x-site.field name="phone" label="Phone Number" type="tel" required autocomplete="tel" />
                        <x-site.field name="email" label="Email" type="email" autocomplete="email" />
                        <x-site.field name="subject" label="Subject" required />
                    </div>
                    <x-site.field name="message" label="Message" type="textarea" required rows="6" />
                    <button type="submit" class="btn-primary w-full py-3.5 sm:w-auto">Send Message <x-site.icon name="arrow-right" class="h-4 w-4" /></button>
                </form>
            </div>
        </div>
    </section>
</x-site-layout>
