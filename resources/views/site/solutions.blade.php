@php
    $solutions = [
        ['key' => 'wifi', 'icon' => 'wifi', 'title' => 'Professional Wi-Fi Solutions', 'desc' => 'From homes and small offices to hotels, schools and large facilities, we design and deploy secure and reliable Wi-Fi networks tailored to your environment.', 'services' => ['Home Wi-Fi', 'Office Wi-Fi', 'Hotel Wi-Fi', 'School Wi-Fi', 'Public Wi-Fi', 'Guest Wi-Fi', 'Hotspot Management', 'Wi-Fi Network Optimization'], 'cta' => 'Request Wi-Fi Solution'],
        ['key' => 'network', 'icon' => 'wrench', 'title' => 'Network Infrastructure & Installation', 'desc' => 'Our technical team can help design, install and configure network infrastructure for homes, businesses and organizations.', 'services' => ['LAN installation', 'Wi-Fi deployment', 'Router configuration', 'Switch configuration', 'Access Point installation', 'Structured cabling', 'Network troubleshooting', 'Network optimization'], 'cta' => 'Request Installation'],
        ['key' => 'enterprise', 'icon' => 'server', 'title' => 'Enterprise Connectivity', 'desc' => 'Customized connectivity solutions for organizations with more demanding networking requirements.', 'services' => ['Enterprise Internet', 'Dedicated Internet Access', 'Point-to-Point Connectivity', 'Site-to-Site Connectivity', 'Network Design', 'VPN Solutions'], 'cta' => 'Request Enterprise Solution'],
        ['key' => 'managed', 'icon' => 'shield', 'title' => 'Managed Connectivity', 'desc' => 'We monitor and manage your connectivity so you can focus on running your organization.', 'services' => ['Network Monitoring', 'Managed Connectivity', 'Network Installation', 'Customer Support'], 'cta' => 'Talk to Sales'],
    ];
@endphp
<x-site-layout>
    <x-slot name="title">Solutions — {{ site('company_name') }}</x-slot>

    <x-site.page-banner eyebrow="Solutions" title="ICT & network solutions, end to end." subtitle="Complete connectivity solutions for homes, businesses and organizations — designed, installed and supported by our technical team.">
        <nav class="mt-10 flex flex-wrap gap-2" aria-label="Solutions">
            @foreach($solutions as $s)
                <a href="#{{ $s['key'] }}" class="chip gap-2"><x-site.icon :name="$s['icon']" class="h-4 w-4" /> {{ $s['title'] }}</a>
            @endforeach
        </nav>
    </x-site.page-banner>

    @foreach($solutions as $i => $s)
        <section id="{{ $s['key'] }}" class="section scroll-mt-20 {{ $i % 2 ? 'section-alt' : '' }}">
            <div class="container-x grid items-start gap-12 lg:grid-cols-12">
                <div class="reveal lg:col-span-5">
                    
                    <span class="icon-tile"><x-site.icon :name="$s['icon']" class="h-6 w-6" /></span>
                    <h2 class="mt-6 text-3xl font-extrabold sm:text-4xl">{{ $s['title'] }}</h2>
                    <p class="lead mt-5">{{ $s['desc'] }}</p>
                    <a href="{{ route('get-connected', ['service' => $s['key']]) }}" class="btn-primary mt-8">{{ $s['cta'] }} <x-site.icon name="arrow-right" class="h-4 w-4" /></a>
                </div>
                <ul class="grid gap-3 sm:grid-cols-2 lg:col-span-7">
                    @foreach($s['services'] as $service)
                        <li class="reveal flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-5 py-4 text-sm font-medium text-slate-700">
                            <span class="inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-brand-50 text-brand-600"><x-site.icon name="check" class="h-3.5 w-3.5" /></span>
                            {{ $service }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endforeach

    @include('site.partials.cta-banner')
</x-site-layout>
