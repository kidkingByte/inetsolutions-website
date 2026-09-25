@php
    $segments = [
        ['icon' => 'home', 'title' => 'Home Internet', 'desc' => 'Streaming, studying, working and video calls for the whole family.', 'href' => route('internet', ['type' => 'home'])],
        ['icon' => 'building', 'title' => 'Business Internet', 'desc' => 'Business-grade connectivity for shops, offices, hotels and schools.', 'href' => route('internet', ['type' => 'business'])],
        ['icon' => 'server', 'title' => 'Enterprise', 'desc' => 'Dedicated access, site-to-site, VPN and managed connectivity.', 'href' => route('internet', ['type' => 'enterprise'])],
    ];
    $services = [
        ['icon' => 'home', 'title' => 'Internet for Your Home', 'desc' => 'Enjoy reliable internet connectivity for streaming, studying, working, video calls, social media and everyday browsing.', 'points' => ['Flexible packages', 'Fast installation', 'Usage monitoring'], 'href' => route('internet', ['type' => 'home']), 'cta' => 'Get Connected'],
        ['icon' => 'building', 'title' => 'Business Internet That Keeps You Moving', 'desc' => 'Reliable connectivity for teams, customers, cloud applications, communication and daily operations.', 'points' => ['Business-grade connectivity', 'Scalable packages', 'Network monitoring'], 'href' => route('internet', ['type' => 'business']), 'cta' => 'Talk to Our Business Team'],
        ['icon' => 'server', 'title' => 'Connectivity for Growing Organizations', 'desc' => 'Customized connectivity for organizations with more demanding networking requirements.', 'points' => ['Dedicated Internet Access', 'Site-to-site & VPN', 'Managed connectivity'], 'href' => route('internet', ['type' => 'enterprise']), 'cta' => 'Request Enterprise Solution'],
        ['icon' => 'wifi', 'title' => 'Professional Wi-Fi Solutions', 'desc' => 'Secure, reliable Wi-Fi designed and deployed for homes, offices, hotels, schools and large facilities.', 'points' => ['Hotel & school Wi-Fi', 'Hotspot management', 'Network optimization'], 'href' => route('solutions').'#wifi', 'cta' => 'Request Wi-Fi Solution'],
    ];
    $steps = [
        ['Check coverage', 'Choose your location and see instantly if INET is available.', 'map-pin'],
        ['Choose a plan', 'Pick the package that fits your home or business.', 'card'],
        ['Get installed', 'Our technical team sets up your connection.', 'wrench'],
        ['Manage in the app', 'Track usage, pay bills and get support from your phone.', 'mobile'],
    ];
    $whyIcons = ['signal', 'sparkles', 'support', 'bolt', 'card', 'building'];
    $whyInet = site_json('why_inet', []);
    $enterprise = ['Enterprise Internet', 'Dedicated Internet Access', 'Point-to-Point Connectivity', 'Site-to-Site Connectivity', 'Network Design', 'Network Installation', 'Network Monitoring', 'Managed Connectivity', 'VPN Solutions'];
    $appFeatures = [['chart', 'Monitor usage'], ['card', 'View billing & pay'], ['bell', 'Get notifications'], ['support', 'Reach support']];
    $allOk = $statuses->every(fn ($s) => $s->isOperational());
    $plans = $featured->isNotEmpty() ? $featured : $homePackages;
@endphp
<x-site-layout>
    {{-- Hero --}}
    <section class="relative isolate overflow-hidden bg-brand-light pb-40 pt-36 sm:pt-44 lg:pb-48">
        <div class="pointer-events-none absolute inset-0 -z-10 bg-wash"></div>
        {{-- The logo's blue slash, cutting across the hero --}}
        <div class="pointer-events-none absolute -top-20 left-[58%] -z-10 hidden h-[130%] w-1 rotate-[28deg] slash-draw rounded-full bg-gradient-to-b from-transparent via-brand-600 to-transparent opacity-60 lg:block"></div>

        <div class="container-x grid items-center gap-14 lg:grid-cols-12">
            <div class="lg:col-span-7">
                @foreach($promotions->take(1) as $promo)
                    <a href="{{ $promo->link ?: route('packages') }}" style="--i:0" class="intro mb-8 inline-flex items-center gap-3 rounded-full border border-accent-200 bg-white py-1.5 pl-1.5 pr-4 text-sm text-slate-700 shadow-sm transition hover:border-accent-300">
                        <span class="rounded-full bg-accent-600 px-2.5 py-0.5 text-xs font-bold text-white">Offer</span>
                        {{ $promo->title }}
                        <x-site.icon name="arrow-right" class="h-4 w-4 text-accent-600" />
                    </a>
                @endforeach

                <p class="eyebrow intro" style="--i:0">{{ site('tagline') }}</p>
                <h1 class="h-display intro mt-6" style="--i:1">
                    Reliable Internet.
                    <span class="block text-gradient pb-1">Built Around Your Needs.</span>
                </h1>
                <p class="lead intro mt-7 max-w-xl" style="--i:2">{{ site('hero_subheading') }}</p>

                <div class="intro mt-10 flex flex-wrap items-center gap-3" style="--i:3">
                    <a href="{{ route('get-connected') }}" class="btn-primary px-7 py-3.5">Get Connected <x-site.icon name="arrow-right" class="h-4 w-4" /></a>
                    <a href="{{ route('packages') }}" class="btn-secondary px-7 py-3.5">View Internet Plans</a>
                </div>

                <p class="intro mt-10 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm font-medium text-slate-600" style="--i:4">
                    <span class="flex items-center gap-2"><x-site.icon name="map-pin" class="h-4 w-4 text-accent-600" /> Based in {{ site('address') }}</span>
                    @if($statuses->isNotEmpty())
                        <a href="{{ route('network-status') }}" class="flex items-center gap-2 hover:text-brand-700">
                            <span class="relative flex h-2 w-2"><span class="absolute inline-flex h-full w-full animate-ping rounded-full {{ $allOk ? 'bg-emerald-400' : 'bg-amber-400' }} opacity-60"></span><span class="relative inline-flex h-2 w-2 rounded-full {{ $allOk ? 'bg-emerald-500' : 'bg-amber-500' }}"></span></span>
                            {{ $allOk ? 'All systems operational' : 'Some services affected' }}
                        </a>
                    @endif
                </p>
            </div>

            {{-- Coverage check over the logo's signal arcs --}}
            <div class="intro relative lg:col-span-5" style="--i:3">
                <x-site.signal-arcs id="hero-arcs" class="pointer-events-none absolute -left-24 -top-24 -z-10 hidden h-64 w-64 sm:block" />
                <div class="glass relative p-6 sm:p-8">
                    <p class="eyebrow">Check coverage</p>
                    <h2 class="mt-3 text-2xl font-bold">Is INET available in your area?</h2>
                    <form method="POST" action="{{ route('coverage.check') }}" class="mt-6 space-y-3">
                        @csrf
                        <x-site.location-picker compact id-prefix="hero" />
                        <button class="btn-primary w-full py-3.5"><x-site.icon name="search" class="h-4 w-4" /> Check Availability</button>
                    </form>
                    <p class="mt-4 text-center text-xs text-slate-500">Or <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', site('whatsapp', '')) }}?text={{ rawurlencode('I want to check coverage in my area.') }}" target="_blank" rel="noopener" class="font-semibold text-brand-600 hover:text-brand-700">ask us on WhatsApp</a></p>
                </div>
            </div>
        </div>
    </section>

    {{-- Audience tiles overlapping the hero --}}
    <section class="relative z-10 -mt-24 lg:-mt-28">
        <div class="container-x grid gap-4 md:grid-cols-3">
            @foreach($segments as $i => $s)
                <a href="{{ $s['href'] }}" class="card card-hover intro group flex items-start gap-5 !p-6" style="--i:{{ 5 + $i }}">
                    <span class="icon-tile shrink-0"><x-site.icon :name="$s['icon']" class="h-6 w-6" /></span>
                    <span>
                        <span class="flex items-center gap-2 text-lg font-bold text-ink">{{ $s['title'] }} <x-site.icon name="arrow-right" class="h-4 w-4 text-brand-600 transition group-hover:translate-x-1" /></span>
                        <span class="mt-1 block text-sm leading-relaxed text-slate-500">{{ $s['desc'] }}</span>
                    </span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Services --}}
    <section class="section">
        <div class="container-x">
            <x-site.section-heading eyebrow="What we do" title="Our Connectivity Solutions"
                subtitle="Whether you need reliable internet for your home, a scalable connection for your business or a complete networking solution for your organization — we design it around your needs." />

            <div class="mt-16 grid gap-6 md:grid-cols-2" data-stagger>
                @foreach($services as $i => $s)
                    <a href="{{ $s['href'] }}" class="card card-hover group flex flex-col overflow-hidden">
                        <span class="bg-signal absolute inset-x-0 top-0 h-1 origin-left scale-x-0 transition duration-500 group-hover:scale-x-100"></span>
                        <span class="icon-tile"><x-site.icon :name="$s['icon']" class="h-6 w-6" /></span>
                        <h3 class="mt-6 text-2xl font-bold">{{ $s['title'] }}</h3>
                        <p class="mt-3 leading-relaxed text-slate-600">{{ $s['desc'] }}</p>
                        <ul class="mt-6 flex-1 space-y-2">
                            @foreach($s['points'] as $point)
                                <li class="flex items-center gap-2.5 text-sm text-slate-700"><x-site.icon name="check" class="h-4 w-4 text-accent-600" /> {{ $point }}</li>
                            @endforeach
                        </ul>
                        <span class="link-arrow mt-8">{{ $s['cta'] }} <x-site.icon name="arrow-right" class="h-4 w-4" /></span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Packages --}}
    @if($plans->isNotEmpty())
    <section class="section section-alt">
        <div class="container-x">
            <x-site.section-heading eyebrow="Internet plans" title="Choose Your Internet Plan" subtitle="Choose an internet package that matches your connectivity needs." />
            <div data-stagger class="mx-auto mt-16 grid gap-6 md:grid-cols-2 {{ $plans->count() >= 3 ? 'lg:grid-cols-3' : 'max-w-4xl' }}">
                @foreach($plans as $package)
                    <x-site.package-card :package="$package" />
                @endforeach
            </div>
            <div class="mt-12 text-center">
                <a href="{{ route('packages') }}" class="btn-secondary">Compare all packages <x-site.icon name="arrow-right" class="h-4 w-4" /></a>
            </div>
        </div>
    </section>
    @endif

    {{-- How it works: steps joined like the logo's "——•——" rule --}}
    <section class="section">
        <div class="container-x">
            <x-site.section-heading eyebrow="How it works" title="From enquiry to online in four steps" />
            <div class="relative mt-16">
            {{-- The connecting rule draws across, then each step and its red marker appear in turn --}}
            <div class="reveal-x pointer-events-none absolute left-[12%] right-[12%] top-7 hidden h-0.5 bg-accent-200 lg:block"></div>
            <ol class="relative grid gap-10 md:grid-cols-2 lg:grid-cols-4 lg:gap-6" data-stagger="160">
                @foreach($steps as $i => [$title, $text, $icon])
                    <li class="relative text-center">
                        <span class="relative mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-white text-brand-600 shadow-lg shadow-slate-900/5 ring-1 ring-slate-200">
                            <x-site.icon :name="$icon" class="h-6 w-6" />
                            <span class="pop absolute -right-1 -top-1 h-4 w-4 rounded-full border-[3px] border-white bg-accent-600"></span>
                        </span>
                        <p class="mt-5 text-xs font-bold uppercase tracking-[0.2em] text-accent-600">Step {{ $i + 1 }}</p>
                        <h3 class="mt-2 text-lg font-bold">{{ $title }}</h3>
                        <p class="mx-auto mt-2 max-w-xs text-sm leading-relaxed text-slate-500">{{ $text }}</p>
                    </li>
                @endforeach
            </ol>
            </div>
        </div>
    </section>

    {{-- Why INET --}}
    @if($whyInet)
    <section class="section section-alt">
        <div class="container-x">
            <x-site.section-heading eyebrow="Why INET" title="Why Choose INET SOLUTIONS?"
                subtitle="Modern networking technology, responsive support and flexible packages — built to keep you connected, productive and competitive." />
            <div class="mt-16 grid gap-6 sm:grid-cols-2 lg:grid-cols-3" data-stagger>
                @foreach($whyInet as $i => $item)
                    <div class="card">
                        <span class="icon-tile"><x-site.icon :name="$whyIcons[$i] ?? 'sparkles'" class="h-6 w-6" /></span>
                        <h3 class="mt-6 text-lg font-bold">{{ $item['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-500">{{ $item['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Business & enterprise: bold brand-blue band --}}
    <section class="section">
        <div class="container-x">
            <div class="reveal relative overflow-hidden rounded-[2rem] bg-brand-700 text-white">
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-br from-brand-600 via-brand-700 to-brand-900"></div>
                <x-site.signal-arcs :animated="false" id="biz-arcs" class="pointer-events-none absolute -bottom-10 -right-10 h-96 w-96 opacity-30" />
                <div class="relative grid gap-12 p-8 sm:p-14 lg:grid-cols-2 lg:items-center">
                    <div>
                        <p class="eyebrow !text-white">Business & Enterprise</p>
                        <h2 class="h-section mt-4 !text-white">Connectivity for growing organizations.</h2>
                        <p class="mt-5 text-lg leading-relaxed text-brand-100">Customized connectivity and network solutions for SMEs, hotels, schools, institutions, NGOs and corporate organizations with demanding requirements.</p>
                        <div class="mt-9 flex flex-wrap gap-3">
                            <a href="{{ route('get-connected', ['service' => 'enterprise']) }}" class="btn bg-white text-brand-700 hover:bg-brand-50">Request Enterprise Solution</a>
                            <a href="{{ route('get-connected', ['service' => 'business']) }}" class="btn border border-white/40 text-white hover:bg-white/10">Talk to Our Business Team</a>
                        </div>
                    </div>
                    <ul class="grid gap-3 sm:grid-cols-2">
                        @foreach($enterprise as $item)
                            <li class="flex items-center gap-3 rounded-xl bg-white/10 px-4 py-3.5 text-sm font-medium text-white ring-1 ring-inset ring-white/15">
                                <x-site.icon name="check" class="h-4 w-4 shrink-0 text-accent-300" /> {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- iNet app --}}
    <section class="section section-alt overflow-hidden">
        <div class="container-x grid items-center gap-16 lg:grid-cols-2">
            {{-- Real iNet App screens: dashboard in front, checkout and splash fanned behind --}}
            <div class="reveal relative order-2 mx-auto w-full max-w-md lg:order-1">
                <x-site.signal-arcs id="app-arcs" class="pointer-events-none absolute -left-16 -top-10 h-72 w-72 opacity-40" />
                <div class="relative flex items-center justify-center py-6">
                    <x-site.phone screen="checkout" alt="iNet App checkout: choose a duration and pay with mobile money"
                        class="absolute left-0 top-12 hidden w-40 -rotate-[8deg] opacity-95 sm:block" />
                    <x-site.phone screen="splash" alt="iNet App start screen"
                        class="absolute right-0 top-12 hidden w-40 rotate-[8deg] opacity-95 sm:block" />
                    <x-site.phone screen="dashboard" alt="iNet App home: active package, days remaining, pay bill, usage and speed test"
                        class="relative z-10 w-56 animate-float sm:w-60" />
                </div>
            </div>

            <div class="order-1 lg:order-2">
                <x-site.section-heading eyebrow="iNet mobile app" title="Manage your internet from your phone." :center="false"
                    subtitle="Download the iNet App and manage your internet connection wherever you are." />
                <ul class="reveal mt-10 grid gap-4 sm:grid-cols-2">
                    @foreach($appFeatures as [$icon, $label])
                        <li class="flex items-center gap-3 font-medium text-slate-700"><span class="icon-tile h-10 w-10"><x-site.icon :name="$icon" class="h-5 w-5" /></span> {{ $label }}</li>
                    @endforeach
                </ul>
                <div class="reveal mt-10 flex flex-wrap items-center gap-3">
                    @include('site.partials.store-badges')
                    <a href="{{ route('app') }}" class="link-arrow ml-1">See the app <x-site.icon name="arrow-right" class="h-4 w-4" /></a>
                </div>
            </div>
        </div>
    </section>

    {{-- Testimonials: real customers only (spec §25) --}}
    @if($testimonials->isNotEmpty())
    <section class="section">
        <div class="container-x">
            <x-site.section-heading eyebrow="Testimonials" title="What Our Customers Say" />
            <div class="mt-16 grid gap-6 md:grid-cols-3">
                @foreach($testimonials as $t)
                    <figure class="card reveal flex flex-col">
                        @if($t->rating)
                            <div class="flex gap-1 text-amber-400" aria-label="{{ $t->rating }} out of 5">
                                @for($r = 0; $r < $t->rating; $r++)<svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.05 2.93c.3-.92 1.6-.92 1.9 0l1.07 3.29a1 1 0 0 0 .95.69h3.46c.97 0 1.37 1.24.59 1.81l-2.8 2.03a1 1 0 0 0-.36 1.12l1.07 3.29c.3.92-.76 1.69-1.54 1.12l-2.8-2.03a1 1 0 0 0-1.18 0l-2.8 2.03c-.78.57-1.84-.2-1.54-1.12l1.07-3.29a1 1 0 0 0-.36-1.12L2.98 8.72c-.78-.57-.38-1.81.59-1.81h3.46a1 1 0 0 0 .95-.69l1.07-3.29Z"/></svg>@endfor
                            </div>
                        @endif
                        <blockquote class="mt-5 flex-1 text-lg leading-relaxed text-slate-700">“{{ $t->quote }}”</blockquote>
                        <figcaption class="mt-6 border-t border-slate-200 pt-5 text-sm font-semibold text-ink">{{ $t->customer_name }}<span class="block font-normal text-slate-500">{{ $t->organization }}</span></figcaption>
                    </figure>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Latest news --}}
    @if($posts->isNotEmpty())
    <section class="section">
        <div class="container-x">
            <div class="flex flex-col justify-between gap-6 sm:flex-row sm:items-end">
                <x-site.section-heading eyebrow="Insights" title="Latest News & Insights" :center="false" />
                <a href="{{ route('news') }}" class="link-arrow reveal shrink-0">All articles <x-site.icon name="arrow-right" class="h-4 w-4" /></a>
            </div>
            <div class="mt-14 grid gap-6 md:grid-cols-3">
                @foreach($posts as $post)
                    @include('site.news._card', ['post' => $post])
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @include('site.partials.cta-banner')
</x-site-layout>
