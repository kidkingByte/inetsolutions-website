<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin') · {{ site('company_name', config('app.name')) }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-ink bg-brand-light antialiased" x-data="{ navOpen: false }">
    <div class="min-h-screen lg:flex">
        {{-- Sidebar --}}
        <aside class="lg:w-64 lg:shrink-0 bg-brand-950 text-white lg:min-h-screen"
               :class="navOpen ? 'block' : 'hidden lg:block'">
            <div class="px-5 py-5 border-b border-white/10 flex items-center gap-2">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-accent font-bold">iN</span>
                <div class="leading-tight">
                    <div class="font-semibold">{{ site('company_name', 'INET Solutions') }}</div>
                    <div class="text-xs text-white/60">Website Admin</div>
                </div>
            </div>

            @php
                $nav = [
                    ['route' => 'admin.dashboard',    'label' => 'Dashboard',    'active' => 'admin.dashboard'],
                    ['route' => 'admin.enquiries.index', 'label' => 'Enquiries',  'active' => 'admin.enquiries.*'],
                    ['route' => 'admin.packages.index', 'label' => 'Packages',    'active' => 'admin.packages.*'],
                    ['route' => 'admin.coverage.index', 'label' => 'Coverage',    'active' => 'admin.coverage.*'],
                    ['route' => 'admin.posts.index',   'label' => 'News & Blog',  'active' => 'admin.posts.*'],
                    ['route' => 'admin.faqs.index',    'label' => 'FAQs',         'active' => 'admin.faqs.*'],
                    ['route' => 'admin.testimonials.index', 'label' => 'Testimonials', 'active' => 'admin.testimonials.*'],
                    ['route' => 'admin.promotions.index', 'label' => 'Promotions', 'active' => 'admin.promotions.*'],
                    ['route' => 'admin.network-status.index', 'label' => 'Network Status', 'active' => 'admin.network-status.*'],
                    ['route' => 'admin.settings.edit', 'label' => 'Settings',     'active' => 'admin.settings.*'],
                ];
            @endphp

            <nav class="px-3 py-4 space-y-1 text-sm">
                @foreach ($nav as $item)
                    <a href="{{ route($item['route']) }}"
                       class="block rounded-lg px-3 py-2 transition {{ request()->routeIs($item['active']) ? 'bg-accent text-white font-semibold' : 'text-white/80 hover:bg-white/10' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="px-3 py-4 border-t border-white/10 text-sm space-y-1">
                <a href="{{ route('home') }}" target="_blank" class="block rounded-lg px-3 py-2 text-white/80 hover:bg-white/10">View Site ↗</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left rounded-lg px-3 py-2 text-white/80 hover:bg-white/10">Log Out</button>
                </form>
            </div>
        </aside>

        {{-- Main --}}
        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-white border-b border-slate-200 px-4 sm:px-6 py-3 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button @click="navOpen = !navOpen" class="lg:hidden inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200">☰</button>
                    <h1 class="text-lg font-semibold">@yield('heading', __('Dashboard'))</h1>
                </div>
                <div class="text-sm text-slate-500">{{ auth()->user()->name }}</div>
            </header>

            <main class="p-4 sm:p-6 flex-1">
                @if (session('status'))
                    <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
