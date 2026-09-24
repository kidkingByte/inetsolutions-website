@php
    $segment = $segment ?: 'home';
    $tabs = ['home' => 'Home Internet', 'business' => 'Business Internet', 'enterprise' => 'Enterprise Internet'];
    $copy = [
        'home' => ['title' => 'Internet for Your Home', 'desc' => 'Enjoy reliable internet connectivity for streaming, studying, working, video calls, social media and everyday browsing.', 'features' => ['Reliable connectivity','Flexible packages','Fast installation','Customer support','Convenient payment options','Usage monitoring']],
        'business' => ['title' => 'Business Internet That Keeps You Moving', 'desc' => 'Our business internet solutions provide reliable connectivity for teams, customers, cloud applications, communication and daily operations.', 'features' => ['Business-grade connectivity','Flexible bandwidth','Scalable packages','Network monitoring','Customer support','Installation support']],
        'enterprise' => ['title' => 'Connectivity for Growing Organizations', 'desc' => 'Customized connectivity for organizations with more demanding networking requirements.', 'features' => ['Dedicated Internet Access','Point-to-Point & Site-to-Site','Network design & installation','VPN solutions','Managed connectivity','Network monitoring']],
    ][$segment];
@endphp
<x-site-layout>
    <x-slot name="title">{{ $copy['title'] }} — {{ site('company_name') }}</x-slot>
    <x-site.page-banner :title="$copy['title']" :subtitle="$copy['desc']" />

    <section class="py-14 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap gap-2">
                @foreach($tabs as $key => $label)
                    <a href="{{ route('internet', ['type' => $key]) }}" class="rounded-full px-4 py-2 text-sm font-semibold {{ $key === $segment ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">{{ $label }}</a>
                @endforeach
            </div>

            <div class="mt-10 grid lg:grid-cols-3 gap-10">
                <div class="lg:col-span-1">
                    <h3 class="text-lg font-bold text-brand-950">What's included</h3>
                    <ul class="mt-4 space-y-3 text-sm text-slate-700">
                        @foreach($copy['features'] as $f)
                            <li class="flex items-start gap-2"><span class="text-green-500">✔</span>{{ $f }}</li>
                        @endforeach
                    </ul>
                    <a href="{{ route('get-connected', ['service' => $segment]) }}" class="mt-6 inline-flex rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-700">Get Connected</a>
                </div>
                <div class="lg:col-span-2 grid sm:grid-cols-2 gap-6">
                    @forelse($packages as $package)
                        <x-site.package-card :package="$package" />
                    @empty
                        <div class="sm:col-span-2 rounded-2xl border border-dashed border-slate-300 p-10 text-center text-slate-500">
                            Packages are being updated. <a href="{{ route('contact') }}" class="text-brand-700 font-semibold">Contact our team</a> for current offers.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    @include('site.partials.cta-banner')
</x-site-layout>
