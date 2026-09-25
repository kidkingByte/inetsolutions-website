@php
    $wa = preg_replace('/[^0-9]/', '', site('whatsapp', ''));
    $options = [
        ['icon' => 'phone', 'title' => 'Call Us', 'desc' => site('phone'), 'href' => 'tel:'.preg_replace('/\s+/', '', site('phone')), 'cta' => 'Call now'],
        ['icon' => 'whatsapp', 'title' => 'WhatsApp Us', 'desc' => 'Chat with our support team', 'href' => 'https://wa.me/'.$wa.'?text='.rawurlencode('I need technical support.'), 'cta' => 'Open WhatsApp'],
        ['icon' => 'document', 'title' => 'Open Support Ticket', 'desc' => 'Report a problem with your connection', 'href' => route('support.report'), 'cta' => 'Submit ticket'],
        ['icon' => 'mail', 'title' => 'Email Support', 'desc' => site('support_email'), 'href' => 'mailto:'.site('support_email'), 'cta' => 'Send email'],
        ['icon' => 'signal', 'title' => 'Report an Outage', 'desc' => 'Check live status or report an outage', 'href' => route('network-status'), 'cta' => 'View status'],
        ['icon' => 'bolt', 'title' => 'Speed Test', 'desc' => 'Measure your connection performance', 'href' => route('speed-test'), 'cta' => 'Start test'],
    ];
@endphp
<x-site-layout>
    <x-slot name="title">Support — {{ site('company_name') }}</x-slot>

    <x-site.page-banner eyebrow="Help center" title="We're Here to Help" subtitle="Having trouble with your connection? Our support team is ready to help you get back online." />

    <section class="section">
        <div class="container-x">
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3" data-stagger>
                @foreach($options as $o)
                    <a href="{{ $o['href'] }}" @if(str_starts_with($o['href'], 'http')) target="_blank" rel="noopener" @endif class="card card-hover reveal group flex flex-col">
                        <span class="icon-tile"><x-site.icon :name="$o['icon']" class="h-6 w-6" /></span>
                        <h2 class="mt-6 text-lg font-bold">{{ $o['title'] }}</h2>
                        <p class="mt-1 flex-1 text-sm text-slate-500">{{ $o['desc'] }}</p>
                        <span class="link-arrow mt-6">{{ $o['cta'] }} <x-site.icon name="arrow-right" class="h-4 w-4" /></span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    @if($faqs->isNotEmpty())
        <section class="section section-alt">
            <div class="container-x">
                <x-site.section-heading eyebrow="FAQs" title="Common Questions" />
                <div class="mx-auto mt-14 max-w-3xl">
                    @include('site.partials.faq-list', ['items' => $faqs])
                    <p class="mt-8 text-center"><a href="{{ route('faq') }}" class="link-arrow">View all FAQs <x-site.icon name="arrow-right" class="h-4 w-4" /></a></p>
                </div>
            </div>
        </section>
    @endif
</x-site-layout>
