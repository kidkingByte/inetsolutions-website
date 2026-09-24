@php
    $services = [
        ['title' => 'Internet for Your Home', 'desc' => 'Reliable internet for streaming, studying, working, video calls and everyday browsing.', 'route' => ['internet', ['type' => 'home']], 'cta' => 'Get Connected'],
        ['title' => 'Business Internet That Keeps You Moving', 'desc' => 'Dependable connectivity for teams, customers, cloud apps and daily operations.', 'route' => ['internet', ['type' => 'business']], 'cta' => 'Talk to Our Business Team'],
        ['title' => 'Connectivity for Growing Organizations', 'desc' => 'Dedicated internet, site-to-site, VPN and managed connectivity, tailored to you.', 'route' => ['internet', ['type' => 'enterprise']], 'cta' => 'Request Enterprise Solution'],
        ['title' => 'Professional Wi-Fi Solutions', 'desc' => 'Secure, reliable Wi-Fi design and deployment for homes, offices, hotels and schools.', 'route' => ['solutions', ['s' => 'wifi']], 'cta' => 'Request Wi-Fi Solution'],
    ];
    $whyInet = site_json('why_inet', []);
@endphp
<x-site-layout>
    {{-- Hero --}}
    <section class="relative bg-gradient-to-br from-brand-700 via-brand-800 to-brand-950 text-white overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image:radial-gradient(circle at 20% 20%, white 1px, transparent 1px); background-size:32px 32px;"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28 grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <span class="inline-flex items-center rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-accent">{{ site('tagline') }}</span>
                <h1 class="mt-4 text-4xl sm:text-5xl font-extrabold tracking-tight leading-tight">{{ site('hero_heading') }}</h1>
                <p class="mt-5 text-lg text-brand-100 max-w-xl">{{ site('hero_subheading') }}</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('get-connected') }}" class="rounded-lg bg-accent px-6 py-3 text-sm font-semibold text-brand-950 hover:bg-accent-400">Get Connected</a>
                    <a href="{{ route('packages') }}" class="rounded-lg bg-white/10 px-6 py-3 text-sm font-semibold text-white ring-1 ring-white/30 hover:bg-white/20">View Internet Plans</a>
                    <a href="{{ route('coverage') }}" class="rounded-lg px-6 py-3 text-sm font-semibold text-brand-100 hover:text-white">Check Coverage →</a>
                </div>
            </div>
            <div class="hidden lg:block">
                <div class="rounded-3xl bg-white/5 ring-1 ring-white/15 p-8 backdrop-blur">
                    <div class="grid grid-cols-3 gap-4 text-center">
                        @foreach($statuses as $s)
                            <div class="rounded-xl bg-white/10 p-4">
                                <div class="mx-auto h-3 w-3 rounded-full {{ $s->isOperational() ? 'bg-green-400' : 'bg-amber-400' }}"></div>
                                <p class="mt-2 text-xs text-brand-100">{{ $s->service }}</p>
                            </div>
                        @endforeach
                    </div>
                    <p class="mt-6 text-center text-sm text-brand-100">All systems operational. <a href="{{ route('network-status') }}" class="text-accent font-semibold hover:underline">View network status</a></p>
                </div>
            </div>
        </div>
    </section>

    {{-- Services --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-site.section-heading title="Our Connectivity Solutions" subtitle="Whether you need reliable internet for your home, a scalable connection for your business or a complete networking solution for your organization, INET SOLUTIONS LTD provides connectivity designed around your needs." />
            <div class="mt-12 grid sm:grid-cols-2 gap-6">
                @foreach($services as $s)
                    <div class="rounded-2xl border border-slate-200 p-8 hover:border-brand-300 hover:shadow-md transition">
                        <h3 class="text-xl font-bold text-brand-950">{{ $s['title'] }}</h3>
                        <p class="mt-3 text-slate-600">{{ $s['desc'] }}</p>
                        <a href="{{ route($s['route'][0], $s['route'][1]) }}" class="mt-5 inline-flex items-center gap-1 text-sm font-semibold text-brand-700 hover:text-brand-800">{{ $s['cta'] }} →</a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Packages --}}
    @if($featured->isNotEmpty())
    <section class="py-20 bg-brand-light">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-site.section-heading title="Choose Your Internet Plan" subtitle="Choose an internet package that matches your connectivity needs." />
            <div class="mt-12 grid md:grid-cols-3 gap-6">
                @foreach(($featured->isNotEmpty() ? $featured : $homePackages) as $package)
                    <x-site.package-card :package="$package" />
                @endforeach
            </div>
            <div class="mt-10 text-center">
                <a href="{{ route('packages') }}" class="inline-flex rounded-lg border border-brand-600 px-6 py-3 text-sm font-semibold text-brand-700 hover:bg-brand-600 hover:text-white">View all packages</a>
            </div>
        </div>
    </section>
    @endif

    {{-- Coverage CTA --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-brand-950">Is INET Available in Your Area?</h2>
                <p class="mt-4 text-slate-600">Check whether INET SOLUTIONS LTD internet services are available at your location.</p>
                <a href="{{ route('coverage') }}" class="mt-6 inline-flex rounded-lg bg-brand-600 px-6 py-3 text-sm font-semibold text-white hover:bg-brand-700">Check Availability</a>
            </div>
            <div class="rounded-3xl bg-brand-950 text-white p-10">
                <p class="text-sm uppercase tracking-widest text-accent">Coverage</p>
                <p class="mt-3 text-2xl font-bold">Fast. Reliable. Connected.</p>
                <ul class="mt-6 space-y-3 text-sm text-brand-100">
                    <li>✅ Fibre to the home &amp; business</li>
                    <li>✅ Wireless &amp; 4G connectivity</li>
                    <li>✅ Expanding across Tanzania</li>
                </ul>
            </div>
        </div>
    </section>

    {{-- Why INET --}}
    @if($whyInet)
    <section class="py-20 bg-brand-light">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-site.section-heading title="Why Choose INET SOLUTIONS?" />
            <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($whyInet as $item)
                    <div class="rounded-2xl bg-white p-6 shadow-sm">
                        <div class="h-10 w-10 rounded-lg bg-brand-600/10 text-brand-700 flex items-center justify-center font-bold">★</div>
                        <h3 class="mt-4 font-bold text-brand-950">{{ $item['title'] }}</h3>
                        <p class="mt-2 text-sm text-slate-600">{{ $item['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- App promotion --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-12 items-center">
            <div class="rounded-3xl bg-gradient-to-br from-accent to-brand-600 p-10 text-white">
                <h3 class="text-2xl font-bold">iNet Mobile App</h3>
                <p class="mt-2 text-brand-50">View account · Check package · Monitor usage · Billing · Notifications · Support</p>
                <div class="mt-6 flex gap-3">
                    <a href="{{ site('app_play_url') }}" target="_blank" rel="noopener" class="rounded-lg bg-brand-950 px-4 py-2 text-sm font-semibold">Google Play</a>
                    @if(site('app_store_url'))<a href="{{ site('app_store_url') }}" target="_blank" rel="noopener" class="rounded-lg bg-brand-950 px-4 py-2 text-sm font-semibold">App Store</a>@endif
                </div>
            </div>
            <div>
                <h2 class="text-3xl font-extrabold tracking-tight text-brand-950">Manage Your Internet From Your Phone</h2>
                <p class="mt-4 text-slate-600">Download the iNet App and manage your internet connection wherever you are.</p>
                <a href="{{ route('app') }}" class="mt-6 inline-flex items-center gap-1 text-sm font-semibold text-brand-700 hover:text-brand-800">Learn about the app →</a>
            </div>
        </div>
    </section>

    {{-- Testimonials --}}
    @if($testimonials->isNotEmpty())
    <section class="py-20 bg-brand-light">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-site.section-heading title="What Our Customers Say" />
            <div class="mt-12 grid md:grid-cols-3 gap-6">
                @foreach($testimonials as $t)
                    <figure class="rounded-2xl bg-white p-6 shadow-sm">
                        <blockquote class="text-slate-700">“{{ $t->quote }}”</blockquote>
                        <figcaption class="mt-4 text-sm font-semibold text-brand-950">{{ $t->customer_name }}<span class="block font-normal text-slate-500">{{ $t->organization }}</span></figcaption>
                    </figure>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Latest news --}}
    @if($posts->isNotEmpty())
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-site.section-heading title="Latest News & Insights" />
            <div class="mt-12 grid md:grid-cols-3 gap-6">
                @foreach($posts as $post)
                    <a href="{{ route('news.show', $post) }}" class="group rounded-2xl border border-slate-200 p-6 hover:border-brand-300 hover:shadow-md transition">
                        <span class="text-xs font-semibold uppercase text-accent">{{ $post->category }}</span>
                        <h3 class="mt-2 text-lg font-bold text-brand-950 group-hover:text-brand-700">{{ $post->title }}</h3>
                        <p class="mt-2 text-sm text-slate-600">{{ $post->excerpt }}</p>
                        <span class="mt-4 inline-block text-sm font-semibold text-brand-700">Read more →</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @include('site.partials.cta-banner')
</x-site-layout>
