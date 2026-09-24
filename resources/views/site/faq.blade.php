<x-site-layout>
    <x-slot name="title">FAQs — {{ site('company_name') }}</x-slot>
    <x-site.page-banner title="Frequently Asked Questions" subtitle="Answers to the questions we hear most." />

    <section class="py-20 bg-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            @forelse($faqs as $category => $items)
                <div>
                    @if($category)
                        <h2 class="text-sm font-bold uppercase tracking-widest text-accent mb-4">{{ $category }}</h2>
                    @endif
                    <div class="divide-y divide-slate-200 rounded-2xl border border-slate-200">
                        <ul class="divide-y divide-slate-200">
                            @foreach($items as $faq)
                                <li x-data="{ open: false }" class="p-5">
                                    <button @click="open = !open" class="flex w-full items-center justify-between text-left font-semibold text-brand-950">
                                        {{ $faq->question }}
                                        <span x-text="open ? '−' : '+'" class="text-brand-600 text-xl"></span>
                                    </button>
                                    <p x-show="open" x-cloak class="mt-3 text-sm text-slate-600">{{ $faq->answer }}</p>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @empty
                <p class="text-center text-slate-500">No FAQs yet.</p>
            @endforelse
        </div>
    </section>

    @include('site.partials.cta-banner')
</x-site-layout>
