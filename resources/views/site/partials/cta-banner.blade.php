@php $wa = preg_replace('/[^0-9]/', '', site('whatsapp', '')); @endphp
<section class="section">
    <div class="container-x">
        <div class="reveal relative overflow-hidden rounded-[2rem] bg-signal px-6 py-16 text-center text-white sm:px-16 sm:py-20">
            <x-site.signal-arcs :animated="false" id="cta-arcs" class="pointer-events-none absolute -left-16 -top-16 h-80 w-80 opacity-20 mix-blend-screen" />
            <div class="pointer-events-none absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>

            <div class="relative">
                <p class="eyebrow justify-center !text-white">Get started</p>
                <h2 class="h-section mx-auto mt-4 max-w-3xl !text-white">Ready to get connected?</h2>
                <p class="mx-auto mt-5 max-w-2xl text-lg text-white/85">Submit your connection request and our team will contact you. Prefer to talk? Reach us on WhatsApp or call us directly.</p>
                <div class="mt-10 flex flex-wrap justify-center gap-3">
                    <a href="{{ route('get-connected') }}" class="btn bg-white text-ink shadow-lg hover:bg-slate-50">Get Connected Today <x-site.icon name="arrow-right" class="h-4 w-4" /></a>
                    @if($wa)
                        <a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener" class="btn-whatsapp"><x-site.icon name="whatsapp" class="h-4 w-4" /> WhatsApp Us</a>
                    @endif
                    <a href="tel:{{ preg_replace('/\s+/', '', site('phone')) }}" class="btn border border-white/50 text-white hover:bg-white/10"><x-site.icon name="phone" class="h-4 w-4" /> {{ site('phone') }}</a>
                </div>
            </div>
        </div>
    </div>
</section>
