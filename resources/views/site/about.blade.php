@php
    $values = site_json('core_values', []);
    $valueIcons = ['signal', 'users', 'sparkles', 'search', 'shield', 'bolt'];
    $serve = ['Home internet users', 'Students & families', 'Small & medium businesses', 'Corporate organizations', 'Schools & institutions', 'Hotels', 'NGOs', 'Government & private organizations'];
@endphp
<x-site-layout>
    <x-slot name="title">About Us — {{ site('company_name') }}</x-slot>

    <x-site.page-banner eyebrow="About INET" :title="site('about_heading')" :subtitle="site('positioning')" />

    <section class="section">
        <div class="container-x grid gap-14 lg:grid-cols-12">
            <div class="lg:col-span-7">
                <div class="prose-dark reveal text-lg">
                    @foreach(preg_split('/\n\s*\n/', site('about_body')) as $para)
                        <p>{{ $para }}</p>
                    @endforeach
                </div>
            </div>
            <div class="lg:col-span-5">
                <div class="card reveal">
                    <p class="eyebrow">Who we serve</p>
                    <ul class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-1 xl:grid-cols-2">
                        @foreach($serve as $item)
                            <li class="flex items-center gap-3 text-sm text-slate-700"><x-site.icon name="check" class="h-4 w-4 shrink-0 text-brand-600" /> {{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="section section-alt">
        <div class="container-x grid gap-6 md:grid-cols-2">
            @foreach([['Our Mission', site('mission'), 'bolt'], ['Our Vision', site('vision'), 'globe']] as [$heading, $text, $icon])
                <div class="card reveal overflow-hidden">
                    <div class="pointer-events-none absolute -right-16 -top-16 h-48 w-48 rounded-full bg-brand-50 blur-2xl"></div>
                    <span class="icon-tile"><x-site.icon :name="$icon" class="h-6 w-6" /></span>
                    <h2 class="mt-6 text-sm font-semibold uppercase tracking-[0.2em] text-brand-600">{{ $heading }}</h2>
                    <p class="mt-4 text-2xl font-semibold leading-snug text-ink">{{ $text }}</p>
                </div>
            @endforeach
        </div>
    </section>

    @if($values)
    <section class="section">
        <div class="container-x">
            <x-site.section-heading eyebrow="What drives us" title="Our Core Values" />
            <div class="mt-16 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($values as $i => $v)
                    <div class="card card-hover reveal">
                        <div class="flex items-center justify-between">
                            <span class="icon-tile"><x-site.icon :name="$valueIcons[$i] ?? 'sparkles'" class="h-6 w-6" /></span>
                        </div>
                        <h3 class="mt-6 text-xl font-bold">{{ $v['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-500">{{ $v['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @include('site.partials.cta-banner')
</x-site-layout>
