@php
    $solutions = [
        ['key' => 'wifi', 'title' => 'Professional Wi-Fi Solutions', 'desc' => 'We design and deploy secure, reliable Wi-Fi tailored to your environment.', 'services' => ['Home Wi-Fi','Office Wi-Fi','Hotel Wi-Fi','School Wi-Fi','Public & Guest Wi-Fi','Hotspot Management','Wi-Fi Network Optimization'], 'cta' => 'Request Wi-Fi Solution'],
        ['key' => 'network', 'title' => 'Network Infrastructure & Installation', 'desc' => 'Our technical team designs, installs and configures network infrastructure.', 'services' => ['LAN installation','Wi-Fi deployment','Router & switch configuration','Access Point installation','Structured cabling','Network troubleshooting','Network optimization'], 'cta' => 'Request Installation'],
        ['key' => 'enterprise', 'title' => 'Enterprise Connectivity', 'desc' => 'Customized connectivity for organizations with demanding requirements.', 'services' => ['Enterprise Internet','Dedicated Internet Access','Point-to-Point & Site-to-Site','Network Design & Installation','VPN Solutions','Managed Connectivity'], 'cta' => 'Request Enterprise Solution'],
        ['key' => 'managed', 'title' => 'Managed Connectivity', 'desc' => 'We monitor and manage your network so you can focus on your business.', 'services' => ['Proactive monitoring','Incident response','Reporting & SLAs','Dedicated support','Capacity planning'], 'cta' => 'Talk to Sales'],
    ];
@endphp
<x-site-layout>
    <x-slot name="title">Solutions — {{ site('company_name') }}</x-slot>
    <x-site.page-banner title="ICT & Network Solutions" subtitle="Complete connectivity solutions for homes, businesses and organizations." />

    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
            @foreach($solutions as $s)
                <div id="{{ $s['key'] }}" class="grid lg:grid-cols-2 gap-10 items-start scroll-mt-20">
                    <div>
                        <h2 class="text-2xl font-extrabold text-brand-950">{{ $s['title'] }}</h2>
                        <p class="mt-3 text-slate-600">{{ $s['desc'] }}</p>
                        <a href="{{ route('get-connected', ['service' => $s['key']]) }}" class="mt-5 inline-flex rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-700">{{ $s['cta'] }}</a>
                    </div>
                    <ul class="grid sm:grid-cols-2 gap-3">
                        @foreach($s['services'] as $service)
                            <li class="rounded-xl bg-brand-light px-4 py-3 text-sm font-medium text-brand-900">{{ $service }}</li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </section>

    @include('site.partials.cta-banner')
</x-site-layout>
