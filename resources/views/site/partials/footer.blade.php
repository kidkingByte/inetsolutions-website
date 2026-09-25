@php
    $social = array_filter(site_json('social_links', []));
    $columns = [
        'Quick Links' => [
            ['Home', route('home')], ['About Us', route('about')], ['Internet', route('internet')],
            ['Packages', route('packages')], ['Solutions', route('solutions')], ['Coverage', route('coverage')],
            ['Support', route('support')], ['Contact', route('contact')],
        ],
        'Customer' => [
            // Account self-service (bills, usage) lives in the iNet app
            ['Download iNet App', route('app')], ['Pay Your Bill', route('app')], ['Check Usage', route('app')],
            ['Support Ticket', route('support.report')], ['Network Status', route('network-status')],
            ['Speed Test', route('speed-test')],
        ],
        'Company' => [
            ['About Us', route('about')], ['News', route('news')], ['FAQs', route('faq')], ['Contact', route('contact')],
            ['Privacy Policy', route('legal', 'privacy-policy')],
            ['Terms & Conditions', route('legal', 'terms-and-conditions')],
            ['Acceptable Use Policy', route('legal', 'acceptable-use-policy')],
        ],
    ];
@endphp
<footer class="relative overflow-hidden bg-night-950">
    <div class="bg-signal h-1"></div>
    <div class="pointer-events-none absolute -top-40 left-1/2 h-80 w-[48rem] -translate-x-1/2 rounded-full bg-brand-600/20 blur-3xl"></div>

    <div class="container-x relative py-16 sm:py-20">
        <div class="grid gap-12 lg:grid-cols-12">
            <div class="lg:col-span-4">
                <img src="{{ asset('images/logo-mark-white.png') }}" alt="{{ site('company_name') }}" class="h-16 w-auto" loading="lazy" width="311" height="280">
                <p class="mt-6 max-w-sm text-sm leading-relaxed text-slate-400">{{ site('positioning') }}</p>
                <p class="mt-4 text-sm font-semibold text-accent-400">{{ site('tagline') }} · {{ site('tagline_sw') }}</p>

                <ul class="mt-8 space-y-3 text-sm">
                    <li class="flex items-center gap-3 text-slate-300"><x-site.icon name="map-pin" class="h-5 w-5 text-accent-400" /> {{ site('address') }}</li>
                    <li><a href="tel:{{ preg_replace('/\s+/', '', site('phone')) }}" class="flex items-center gap-3 text-slate-300 hover:text-white"><x-site.icon name="phone" class="h-5 w-5 text-accent-400" /> {{ site('phone') }}</a></li>
                    <li><a href="mailto:{{ site('email') }}" class="flex items-center gap-3 text-slate-300 hover:text-white"><x-site.icon name="mail" class="h-5 w-5 text-accent-400" /> {{ site('email') }}</a></li>
                </ul>
            </div>

            <div class="grid grid-cols-2 gap-10 sm:grid-cols-3 lg:col-span-8">
                @foreach($columns as $heading => $links)
                    <div>
                        <h4 class="text-sm font-semibold text-white">{{ $heading }}</h4>
                        <ul class="mt-5 space-y-3 text-sm">
                            @foreach($links as [$label, $href])
                                <li><a href="{{ $href }}" class="text-slate-400 transition hover:text-white">{{ $label }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-16 flex flex-col-reverse items-start justify-between gap-6 border-t border-white/10 pt-8 sm:flex-row sm:items-center">
            <p class="text-sm text-slate-500">&copy; {{ date('Y') }} {{ site('company_name') }}. All Rights Reserved.</p>
            @if($social)
                <div class="flex items-center gap-2">
                    <span class="mr-2 text-sm text-slate-500">Follow us</span>
                    @foreach($social as $network => $url)
                        <a href="{{ $url }}" target="_blank" rel="noopener" aria-label="{{ ucfirst($network) }}"
                           class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/10 text-slate-300 transition hover:border-accent-400/50 hover:text-white">
                            <x-site.icon :name="$network" class="h-4 w-4" />
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</footer>
