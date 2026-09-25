@php
    $segment = in_array($segment, ['home', 'business', 'enterprise'], true) ? $segment : 'home';
    $tabs = ['home' => ['Home Internet', 'home'], 'business' => ['Business Internet', 'building'], 'enterprise' => ['Enterprise Internet', 'server']];
    $copy = [
        'home' => ['title' => 'Internet for Your Home', 'desc' => 'Enjoy reliable internet connectivity for streaming, studying, working, video calls, social media and everyday browsing.', 'features' => ['Reliable connectivity', 'Flexible packages', 'Fast installation', 'Customer support', 'Convenient payment options', 'Usage monitoring'], 'suitable' => ['Families', 'Students', 'Remote workers', 'Streaming & video calls'], 'cta' => 'Get Connected'],
        'business' => ['title' => 'Business Internet That Keeps You Moving', 'desc' => 'Your business depends on connectivity. Our business internet solutions provide reliable connectivity for teams, customers, cloud applications, communication and daily operations.', 'features' => ['Business-grade connectivity', 'Flexible bandwidth', 'Scalable packages', 'Network monitoring', 'Customer support', 'Installation support'], 'suitable' => ['Small businesses', 'Shops', 'Offices', 'Hotels', 'Schools', 'SMEs', 'Institutions', 'Organizations'], 'cta' => 'Talk to Our Business Team'],
        'enterprise' => ['title' => 'Connectivity for Growing Organizations', 'desc' => 'INET SOLUTIONS LTD provides customized connectivity solutions for organizations with more demanding networking requirements.', 'features' => ['Enterprise Internet', 'Dedicated Internet Access', 'Point-to-Point Connectivity', 'Site-to-Site Connectivity', 'Network Design & Installation', 'Network Monitoring', 'Managed Connectivity', 'VPN Solutions'], 'suitable' => ['Corporate organizations', 'Institutions', 'NGOs', 'Government & private organizations'], 'cta' => 'Request Enterprise Solution'],
    ][$segment];
@endphp
<x-site-layout>
    <x-slot name="title">{{ $tabs[$segment][0] }} — {{ site('company_name') }}</x-slot>

    <x-site.page-banner :eyebrow="$tabs[$segment][0]" :title="$copy['title']" :subtitle="$copy['desc']">
        <nav class="mt-10 flex flex-wrap gap-2" aria-label="Internet segments">
            @foreach($tabs as $key => [$label, $icon])
                <a href="{{ route('internet', ['type' => $key]) }}" @if($key === $segment) aria-current="page" @endif class="chip gap-2 {{ $key === $segment ? 'chip-active' : '' }}">
                    <x-site.icon :name="$icon" class="h-4 w-4" /> {{ $label }}
                </a>
            @endforeach
        </nav>
    </x-site.page-banner>

    <section class="section">
        <div class="container-x grid gap-12 lg:grid-cols-12">
            <aside class="lg:col-span-4">
                <div class="card reveal lg:sticky lg:top-28">
                    <h2 class="text-lg font-bold">What's included</h2>
                    <ul class="mt-5 space-y-3 text-sm">
                        @foreach($copy['features'] as $f)
                            <li class="flex items-start gap-3 text-slate-700"><x-site.icon name="check" class="mt-0.5 h-4 w-4 shrink-0 text-brand-600" />{{ $f }}</li>
                        @endforeach
                    </ul>
                    <h3 class="mt-8 text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Suitable for</h3>
                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach($copy['suitable'] as $s)
                            <span class="rounded-full border border-slate-200 px-3 py-1 text-xs text-slate-600">{{ $s }}</span>
                        @endforeach
                    </div>
                    <a href="{{ route('get-connected', ['service' => $segment]) }}" class="btn-primary mt-8 w-full">{{ $copy['cta'] }}</a>
                    <a href="{{ route('coverage') }}" class="btn-secondary mt-3 w-full">Check Coverage</a>
                </div>
            </aside>

            <div class="grid gap-6 sm:grid-cols-2 lg:col-span-8">
                @forelse($packages as $package)
                    <x-site.package-card :package="$package" />
                @empty
                    <div class="card sm:col-span-2 text-center">
                        <p class="text-slate-600">Packages are being updated.</p>
                        <a href="{{ route('contact') }}" class="link-arrow mt-3">Contact our team for current offers <x-site.icon name="arrow-right" class="h-4 w-4" /></a>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    @include('site.partials.cta-banner')
</x-site-layout>
