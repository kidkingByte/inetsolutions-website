@php
    $social = site_json('social_links', []);
    $quickLinks = [
        ['Home', 'home'], ['About Us', 'about'], ['Internet', 'internet'], ['Packages', 'packages'],
        ['Solutions', 'solutions'], ['Coverage', 'coverage'], ['Support', 'support'], ['Contact', 'contact'],
    ];
    $customerLinks = [
        ['Customer Login', site('customer_portal_url') ?: route('login')],
        ['Pay Your Bill', site('customer_portal_url') ?: route('login')],
        ['Check Usage', site('customer_portal_url') ?: route('login')],
        ['Support Ticket', route('support.report')],
        ['Download App', route('app')],
    ];
    $companyLinks = [
        ['About Us', route('about')], ['News', route('news')], ['Contact', route('contact')],
        ['Privacy Policy', route('legal', 'privacy-policy')],
        ['Terms & Conditions', route('legal', 'terms-and-conditions')],
        ['Acceptable Use Policy', route('legal', 'acceptable-use-policy')],
    ];
@endphp
<footer class="bg-brand-950 text-slate-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-10">
            <div class="lg:col-span-2">
                <div class="flex items-center gap-2">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-brand-600 text-white font-extrabold">iN</span>
                    <span class="font-extrabold text-lg tracking-tight text-white">INET<span class="text-accent">SOLUTIONS</span></span>
                </div>
                <p class="mt-4 text-sm max-w-sm">{{ site('positioning') }}</p>
                <p class="mt-4 text-sm italic text-accent">{{ site('tagline') }}</p>
            </div>

            <div>
                <h4 class="text-white font-semibold mb-4 text-sm">Quick Links</h4>
                <ul class="space-y-2 text-sm">
                    @foreach($quickLinks as [$label, $route])
                        <li><a href="{{ route($route) }}" class="hover:text-white">{{ $label }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h4 class="text-white font-semibold mb-4 text-sm">Customer</h4>
                <ul class="space-y-2 text-sm">
                    @foreach($customerLinks as [$label, $href])
                        <li><a href="{{ $href }}" class="hover:text-white">{{ $label }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h4 class="text-white font-semibold mb-4 text-sm">Company</h4>
                <ul class="space-y-2 text-sm">
                    @foreach($companyLinks as [$label, $href])
                        <li><a href="{{ $href }}" class="hover:text-white">{{ $label }}</a></li>
                    @endforeach
                </ul>
                <h4 class="text-white font-semibold mt-6 mb-3 text-sm">Follow Us</h4>
                <div class="flex gap-3">
                    @foreach($social as $network => $url)
                        @if($url)
                            <a href="{{ $url }}" target="_blank" rel="noopener" class="h-8 w-8 rounded-full bg-white/10 hover:bg-brand-600 flex items-center justify-center text-xs capitalize" title="{{ ucfirst($network) }}">{{ strtoupper(substr($network,0,1)) }}</a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mt-12 border-t border-white/10 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-sm">
            <p>&copy; {{ date('Y') }} {{ site('company_name') }}. All Rights Reserved.</p>
            <p class="text-slate-400">{{ site('address') }} &middot; <a href="tel:{{ preg_replace('/\s+/','', site('phone')) }}" class="hover:text-white">{{ site('phone') }}</a></p>
        </div>
    </div>
</footer>
