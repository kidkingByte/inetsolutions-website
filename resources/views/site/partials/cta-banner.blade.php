<section class="bg-gradient-to-r from-brand-700 to-accent-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
        <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Ready to Get Connected?</h2>
        <p class="mt-3 text-brand-50 max-w-2xl mx-auto">Submit your connection request and our team will contact you. Prefer to talk? Reach us on WhatsApp or call us directly.</p>
        <div class="mt-8 flex flex-wrap justify-center gap-3">
            <a href="{{ route('get-connected') }}" class="rounded-lg bg-white px-6 py-3 text-sm font-semibold text-brand-700 hover:bg-brand-50">Get Connected Today</a>
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/','', site('whatsapp','')) }}" target="_blank" rel="noopener" class="rounded-lg bg-green-500 px-6 py-3 text-sm font-semibold text-white hover:bg-green-600">WhatsApp Us</a>
            <a href="tel:{{ preg_replace('/\s+/','', site('phone')) }}" class="rounded-lg ring-1 ring-white/40 px-6 py-3 text-sm font-semibold text-white hover:bg-white/10">{{ site('phone') }}</a>
        </div>
    </div>
</section>
