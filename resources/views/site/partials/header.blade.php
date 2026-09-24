@php
    $nav = [
        ['label' => 'Home', 'route' => 'home'],
        ['label' => 'About Us', 'route' => 'about'],
        ['label' => 'Internet', 'route' => 'internet'],
        ['label' => 'Solutions', 'route' => 'solutions'],
        ['label' => 'Packages', 'route' => 'packages'],
        ['label' => 'Coverage', 'route' => 'coverage'],
        ['label' => 'Support', 'route' => 'support'],
        ['label' => 'App', 'route' => 'app'],
        ['label' => 'News', 'route' => 'news'],
        ['label' => 'Contact', 'route' => 'contact'],
    ];
@endphp
<header x-data="{ open: false }" class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-brand-600 text-white font-extrabold">iN</span>
                <span class="font-extrabold text-lg tracking-tight text-brand-950">INET<span class="text-brand-600">SOLUTIONS</span></span>
            </a>

            <nav class="hidden lg:flex items-center gap-1">
                @foreach($nav as $item)
                    <a href="{{ route($item['route']) }}"
                       class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs($item['route']) ? 'text-brand-700 bg-brand-50' : 'text-slate-700 hover:text-brand-700 hover:bg-slate-50' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="hidden lg:flex items-center gap-3">
                <a href="{{ site('customer_portal_url') ?: route('login') }}" class="text-sm font-semibold text-slate-700 hover:text-brand-700">Customer Login</a>
                <a href="{{ route('get-connected') }}" class="inline-flex items-center rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brand-700">Get Connected</a>
            </div>

            <button @click="open = !open" class="lg:hidden inline-flex items-center justify-center rounded-md p-2 text-slate-700 hover:bg-slate-100">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>

    <div x-show="open" x-cloak class="lg:hidden border-t border-slate-100 bg-white">
        <div class="px-4 py-3 space-y-1">
            @foreach($nav as $item)
                <a href="{{ route($item['route']) }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs($item['route']) ? 'text-brand-700 bg-brand-50' : 'text-slate-700 hover:bg-slate-50' }}">{{ $item['label'] }}</a>
            @endforeach
            <div class="pt-2 flex flex-col gap-2">
                <a href="{{ site('customer_portal_url') ?: route('login') }}" class="text-center px-3 py-2 rounded-md border border-slate-200 font-semibold text-slate-700">Customer Login</a>
                <a href="{{ route('get-connected') }}" class="text-center px-3 py-2 rounded-md bg-brand-600 font-semibold text-white">Get Connected</a>
            </div>
        </div>
    </div>
</header>
