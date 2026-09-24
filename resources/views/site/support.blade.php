@php
    $wa = preg_replace('/[^0-9]/','', site('whatsapp',''));
    $options = [
        ['title' => 'Call Us', 'desc' => site('phone'), 'href' => 'tel:'.preg_replace('/\s+/','', site('phone')), 'cta' => 'Call now'],
        ['title' => 'WhatsApp Us', 'desc' => 'Chat with our support team', 'href' => 'https://wa.me/'.$wa, 'cta' => 'Open WhatsApp'],
        ['title' => 'Report a Problem', 'desc' => 'Open a support ticket', 'href' => route('support.report'), 'cta' => 'Submit ticket'],
        ['title' => 'Email Support', 'desc' => site('support_email'), 'href' => 'mailto:'.site('support_email'), 'cta' => 'Send email'],
        ['title' => 'Network Status', 'desc' => 'Check for outages in your area', 'href' => route('network-status'), 'cta' => 'View status'],
    ];
@endphp
<x-site-layout>
    <x-slot name="title">Support — {{ site('company_name') }}</x-slot>
    <x-site.page-banner title="We're Here to Help" subtitle="Having trouble with your connection? Our support team is ready to help you get back online." />

    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($options as $o)
                    <div class="rounded-2xl border border-slate-200 p-6 flex flex-col">
                        <h3 class="font-bold text-brand-950">{{ $o['title'] }}</h3>
                        <p class="mt-1 text-sm text-slate-600 flex-1">{{ $o['desc'] }}</p>
                        <a href="{{ $o['href'] }}" {{ str_starts_with($o['href'],'http') ? 'target=_blank rel=noopener' : '' }} class="mt-4 inline-flex text-sm font-semibold text-brand-700 hover:text-brand-800">{{ $o['cta'] }} →</a>
                    </div>
                @endforeach
            </div>

            @if($faqs->isNotEmpty())
                <div class="mt-16">
                    <x-site.section-heading title="Common Questions" />
                    <div class="mt-8 max-w-3xl mx-auto divide-y divide-slate-200 rounded-2xl border border-slate-200">
                        @foreach($faqs as $faq)
                            <div class="p-5">
                                <p class="font-semibold text-brand-950">{{ $faq->question }}</p>
                                <p class="mt-1 text-sm text-slate-600">{{ $faq->answer }}</p>
                            </div>
                        @endforeach
                    </div>
                    <p class="mt-6 text-center"><a href="{{ route('faq') }}" class="text-brand-700 font-semibold">View all FAQs →</a></p>
                </div>
            @endif
        </div>
    </section>
</x-site-layout>
