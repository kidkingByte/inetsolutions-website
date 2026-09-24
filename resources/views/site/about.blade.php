@php $values = site_json('core_values', []); @endphp
<x-site-layout>
    <x-slot name="title">{{ site('about_heading') }} — {{ site('company_name') }}</x-slot>

    <x-site.page-banner :title="site('about_heading')" :subtitle="site('tagline')" />

    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="prose prose-slate max-w-none text-slate-700 space-y-4">
                @foreach(preg_split('/\n\s*\n/', site('about_body')) as $para)
                    <p>{{ $para }}</p>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-16 bg-brand-light">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-2 gap-6">
            <div class="rounded-2xl bg-white p-8 shadow-sm">
                <h3 class="text-sm font-bold uppercase tracking-widest text-accent">Our Mission</h3>
                <p class="mt-3 text-slate-700">{{ site('mission') }}</p>
            </div>
            <div class="rounded-2xl bg-white p-8 shadow-sm">
                <h3 class="text-sm font-bold uppercase tracking-widest text-accent">Our Vision</h3>
                <p class="mt-3 text-slate-700">{{ site('vision') }}</p>
            </div>
        </div>
    </section>

    @if($values)
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-site.section-heading title="Our Core Values" />
            <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($values as $v)
                    <div class="rounded-2xl border border-slate-200 p-6">
                        <h3 class="font-bold text-brand-950">{{ $v['title'] }}</h3>
                        <p class="mt-2 text-sm text-slate-600">{{ $v['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @include('site.partials.cta-banner')
</x-site-layout>
