@php
    $features = ['View account','Check internet package','Monitor usage','View billing information','Manage account','Receive notifications','Access customer support','View service information','Manage internet services'];
@endphp
<x-site-layout>
    <x-slot name="title">iNet Mobile App — {{ site('company_name') }}</x-slot>
    <x-site.page-banner title="Manage Your Internet From Your Phone" subtitle="Download the iNet App and manage your internet connection wherever you are.">
        <div class="mt-8 flex flex-wrap gap-3">
            @if(site('app_play_url'))<a href="{{ site('app_play_url') }}" target="_blank" rel="noopener" class="rounded-lg bg-white px-5 py-3 text-sm font-semibold text-brand-700 hover:bg-brand-50">Get it on Google Play</a>@endif
            @if(site('app_store_url'))<a href="{{ site('app_store_url') }}" target="_blank" rel="noopener" class="rounded-lg bg-white px-5 py-3 text-sm font-semibold text-brand-700 hover:bg-brand-50">Download on the App Store</a>@endif
        </div>
    </x-site.page-banner>

    <section class="py-20 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-site.section-heading title="Everything, in your pocket" center="true" />
            <ul class="mt-12 grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($features as $f)
                    <li class="flex items-center gap-3 rounded-xl bg-brand-light px-5 py-4 text-slate-700"><span class="text-green-500">✔</span>{{ $f }}</li>
                @endforeach
            </ul>
        </div>
    </section>

    @include('site.partials.cta-banner')
</x-site-layout>
