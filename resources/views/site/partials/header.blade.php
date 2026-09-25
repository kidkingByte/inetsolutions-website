@php
    $wa = preg_replace('/[^0-9]/', '', site('whatsapp', ''));
    // Grouped so the full spec navigation fits on one line; the mobile menu lists everything.
    $nav = [
        ['label' => 'Internet', 'active' => 'internet', 'children' => [
            ['Home Internet', route('internet', ['type' => 'home']), 'home', 'Streaming, study & work from home'],
            ['Business Internet', route('internet', ['type' => 'business']), 'building', 'Business-grade connectivity'],
            ['Enterprise Internet', route('internet', ['type' => 'enterprise']), 'server', 'Dedicated access, VPN & more'],
        ]],
        ['label' => 'Solutions', 'href' => route('solutions'), 'active' => 'solutions'],
        ['label' => 'Packages', 'href' => route('packages'), 'active' => 'packages'],
        ['label' => 'Coverage', 'href' => route('coverage'), 'active' => 'coverage*'],
        ['label' => 'Support', 'active' => ['support*', 'network-status', 'speed-test', 'faq'], 'children' => [
            ['Help Center', route('support'), 'support', 'Call, WhatsApp or email us'],
            ['Report a Problem', route('support.report'), 'alert', 'Open a support ticket'],
            ['Network Status', route('network-status'), 'signal', 'Live service status'],
            ['Speed Test', route('speed-test'), 'bolt', 'Test your connection'],
            ['FAQs', route('faq'), 'document', 'Quick answers'],
        ]],
        ['label' => 'App', 'href' => route('app'), 'active' => 'app'],
        ['label' => 'Company', 'active' => ['about', 'news*', 'contact'], 'children' => [
            ['About Us', route('about'), 'users', 'Mission, vision & values'],
            ['News & Insights', route('news'), 'sparkles', 'Tips, tech & updates'],
            ['Contact', route('contact'), 'mail', 'Get in touch with INET'],
        ]],
    ];
@endphp
<header x-data="{ open: false, scrolled: false }"
        x-init="scrolled = window.scrollY > 10"
        @scroll.window="scrolled = window.scrollY > 10"
        @keydown.escape.window="open = false"
        :class="scrolled || open ? 'shadow-lg shadow-slate-900/5 border-slate-200' : 'border-transparent'"
        class="fixed inset-x-0 top-0 z-50 border-b bg-white/95 backdrop-blur-xl transition duration-300">
    {{-- Utility bar --}}
    <div class="hidden bg-ink text-xs text-slate-300 transition-all duration-300 lg:block" :class="scrolled ? 'h-0 overflow-hidden opacity-0' : 'h-9 opacity-100'">
        <div class="container-x flex h-9 items-center justify-between gap-6">
            <div class="flex items-center gap-6">
                <span class="flex items-center gap-2"><x-site.icon name="map-pin" class="h-3.5 w-3.5 text-accent-400" /> {{ site('address') }}</span>
                <a href="tel:{{ preg_replace('/\s+/', '', site('phone')) }}" class="flex items-center gap-2 hover:text-white"><x-site.icon name="phone" class="h-3.5 w-3.5 text-accent-400" /> {{ site('phone') }}</a>
                <a href="mailto:{{ site('email') }}" class="flex items-center gap-2 hover:text-white"><x-site.icon name="mail" class="h-3.5 w-3.5 text-accent-400" /> {{ site('email') }}</a>
            </div>
            <div class="flex items-center gap-6">
                <a href="{{ route('network-status') }}" class="flex items-center gap-2 hover:text-white"><span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span> Network status</a>
                <a href="{{ route('speed-test') }}" class="hover:text-white">Speed test</a>
                @if($wa)<a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener" class="flex items-center gap-1.5 hover:text-white"><x-site.icon name="whatsapp" class="h-3.5 w-3.5 text-[#25D366]" /> WhatsApp</a>@endif
            </div>
        </div>
    </div>

    <div class="container-x">
        <div class="flex h-[76px] items-center justify-between gap-6">
            <a href="{{ route('home') }}" class="flex shrink-0 items-center" aria-label="{{ site('company_name') }} — Home">
                <img src="{{ asset('images/logo-color-mark.png') }}" alt="{{ site('company_name') }}" class="h-[3.25rem] w-auto" width="311" height="280">
            </a>

            <nav class="hidden items-center gap-1 xl:flex" aria-label="Main">
                @foreach($nav as $item)
                    @php $isActive = request()->routeIs(...(array) $item['active']); @endphp
                    @isset($item['children'])
                        <div class="relative" x-data="{ menu: false }" @mouseenter="menu = true" @mouseleave="menu = false">
                            <button type="button" @click="menu = !menu" :aria-expanded="menu"
                                    class="inline-flex items-center gap-1 rounded-full px-3.5 py-2 text-sm font-semibold transition {{ $isActive ? 'text-brand-700' : 'text-slate-700 hover:text-brand-700' }}">
                                {{ $item['label'] }}
                                <x-site.icon name="chevron-down" class="h-3.5 w-3.5 transition" ::class="menu && 'rotate-180'" />
                            </button>
                            <div x-show="menu" x-cloak x-transition.opacity.duration.150ms class="absolute left-1/2 top-full w-80 -translate-x-1/2 pt-3">
                                <div class="glass p-2">
                                    @foreach($item['children'] as [$label, $href, $icon, $hint])
                                        <a href="{{ $href }}" class="flex items-start gap-3 rounded-2xl p-3 transition hover:bg-brand-light">
                                            <span class="icon-tile h-10 w-10 shrink-0"><x-site.icon :name="$icon" class="h-5 w-5" /></span>
                                            <span>
                                                <span class="block text-sm font-semibold text-ink">{{ $label }}</span>
                                                <span class="block text-xs text-slate-500">{{ $hint }}</span>
                                            </span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ $item['href'] }}" @if($isActive) aria-current="page" @endif
                           class="rounded-full px-3.5 py-2 text-sm font-semibold transition {{ $isActive ? 'text-brand-700' : 'text-slate-700 hover:text-brand-700' }}">{{ $item['label'] }}</a>
                    @endisset
                @endforeach
            </nav>

            <div class="hidden items-center gap-2 xl:flex">
                {{-- Customers manage their account in the iNet app, so the site links there instead of a login --}}
                <a href="{{ route('app') }}" class="inline-flex min-h-11 items-center gap-2 rounded-full px-4 py-2 text-sm font-semibold text-slate-700 transition hover:text-brand-700">
                    <x-site.icon name="mobile" class="h-4 w-4" /> Get the App
                </a>
                <a href="{{ route('get-connected') }}" class="btn-primary px-5 py-2.5">Get Connected</a>
            </div>

            <button type="button" @click="open = !open" class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 text-ink xl:hidden" :aria-expanded="open" aria-label="Toggle menu">
                <x-site.icon name="menu" class="h-5 w-5" x-show="!open" />
                <x-site.icon name="x" class="h-5 w-5" x-show="open" x-cloak />
            </button>
        </div>
    </div>
    {{-- Brand rule: the logo's violet → red signal gradient --}}
    <div class="bg-signal h-[3px]"></div>

    {{-- Mobile menu --}}
    <div x-show="open" x-cloak x-transition.opacity class="max-h-[calc(100vh-80px)] overflow-y-auto bg-white xl:hidden">
        <div class="container-x space-y-6 py-6">
            <a href="{{ route('home') }}" class="block text-lg font-semibold text-ink">Home</a>
            @foreach($nav as $item)
                @isset($item['children'])
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-accent-600">{{ $item['label'] }}</p>
                        <div class="mt-3 grid gap-1 sm:grid-cols-2">
                            @foreach($item['children'] as [$label, $href, $icon])
                                <a href="{{ $href }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-slate-700 hover:bg-brand-light">
                                    <x-site.icon :name="$icon" class="h-5 w-5 text-brand-600" /> {{ $label }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ $item['href'] }}" class="block text-lg font-semibold text-ink">{{ $item['label'] }}</a>
                @endisset
            @endforeach
            <div class="grid gap-3 border-t border-slate-200 pt-6 sm:grid-cols-2">
                <a href="{{ route('app') }}" class="btn-secondary"><x-site.icon name="mobile" class="h-4 w-4" /> Get the App</a>
                <a href="{{ route('get-connected') }}" class="btn-primary">Get Connected</a>
            </div>
        </div>
    </div>
</header>
