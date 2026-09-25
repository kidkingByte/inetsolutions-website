<x-site-layout>
    <x-slot name="title">FAQs — {{ site('company_name') }}</x-slot>

    <x-site.page-banner eyebrow="FAQs" title="Frequently Asked Questions" subtitle="Answers to the questions we hear most." />

    <section class="section">
        <div class="container-x max-w-4xl space-y-14">
            @forelse($faqs as $category => $items)
                <div>
                    @if($category)
                        <h2 class="eyebrow mb-6">{{ $category }}</h2>
                    @endif
                    @include('site.partials.faq-list', ['items' => $items])
                </div>
            @empty
                <p class="text-center text-slate-500">No FAQs yet.</p>
            @endforelse
        </div>
    </section>

    @include('site.partials.cta-banner')
</x-site-layout>
