<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex">

    <title>@yield('title', 'Admin') · {{ site('company_name', config('app.name')) }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@php
    $user = auth()->user();
    $leadsActive = request()->routeIs('admin.enquiries.*') && request('area') !== 'support' && request('type') !== 'support_request';
    // Each group only shows the links this staff member's role allows (config/roles.php).
    $groups = [
        'Overview' => [
            ['Dashboard', route('admin.dashboard'), 'chart', request()->routeIs('admin.dashboard'), true, null],
        ],
        'Leads & support' => [
            ['Sales leads', route('admin.enquiries.index', ['area' => 'leads']), 'users', $leadsActive, $user->can('leads.view'), $navBadges['leads'] ?? 0],
            ['Support tickets', route('admin.enquiries.index', ['area' => 'support']), 'support', request()->routeIs('admin.enquiries.*') && ! $leadsActive, $user->can('support.view'), $navBadges['support'] ?? 0],
        ],
        'Website' => [
            ['Packages', route('admin.packages.index'), 'card', request()->routeIs('admin.packages.*'), $user->can('packages.manage'), null],
            ['Coverage areas', route('admin.coverage.index'), 'map-pin', request()->routeIs('admin.coverage.*'), $user->can('coverage.manage'), null],
            ['Network status', route('admin.network-status.index'), 'signal', request()->routeIs('admin.network-status.*'), $user->can('network.manage'), null],
            ['News & blog', route('admin.posts.index'), 'document', request()->routeIs('admin.posts.*'), $user->can('content.manage'), null],
            ['FAQs', route('admin.faqs.index'), 'support', request()->routeIs('admin.faqs.*'), $user->can('content.manage'), null],
            ['Testimonials', route('admin.testimonials.index'), 'sparkles', request()->routeIs('admin.testimonials.*'), $user->can('content.manage'), null],
            ['Promotions', route('admin.promotions.index'), 'bolt', request()->routeIs('admin.promotions.*'), $user->can('content.manage'), null],
        ],
        'System' => [
            ['Staff & roles', route('admin.users.index'), 'lock', request()->routeIs('admin.users.*'), $user->can('users.manage'), null],
            ['Audit log', route('admin.audit.index'), 'shield', request()->routeIs('admin.audit.*'), $user->can('audit.view'), null],
            ['Settings', route('admin.settings.edit'), 'wrench', request()->routeIs('admin.settings.*'), $user->can('settings.manage'), null],
        ],
    ];
@endphp
<body class="bg-brand-light font-sans text-slate-700 antialiased" x-data="{ navOpen: false }" @keydown.escape.window="navOpen = false">
    <div class="min-h-screen lg:flex">
        {{-- Mobile overlay --}}
        <div x-show="navOpen" x-cloak x-transition.opacity @click="navOpen = false" class="fixed inset-0 z-40 bg-ink/40 lg:hidden"></div>

        {{-- Sidebar --}}
        <aside class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col border-r border-slate-200 bg-white transition-transform duration-200 lg:sticky lg:top-0 lg:h-screen lg:translate-x-0"
               :class="navOpen && '!translate-x-0'" aria-label="Admin navigation">
            <div class="flex items-center justify-between gap-3 px-5 py-4">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/logo-color-mark.png') }}" alt="{{ site('company_name') }}" class="h-11 w-auto">
                    <span class="leading-tight">
                        <span class="block text-sm font-bold text-ink">Management</span>
                        <span class="block text-xs text-slate-500">{{ site('company_name') }}</span>
                    </span>
                </a>
                <button type="button" @click="navOpen = false" class="inline-flex h-11 w-11 items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 lg:hidden" aria-label="Close menu">
                    <x-site.icon name="x" class="h-5 w-5" />
                </button>
            </div>
            <div class="bg-signal h-[3px]"></div>

            <nav class="flex-1 space-y-6 overflow-y-auto px-3 py-5 text-sm">
                @foreach ($groups as $heading => $links)
                    @php $links = array_filter($links, fn ($l) => $l[4]); @endphp
                    @continue(empty($links))
                    <div>
                        <p class="px-3 text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400">{{ $heading }}</p>
                        <div class="mt-2 space-y-0.5">
                            @foreach ($links as [$label, $href, $icon, $active, $allowed, $badge])
                                <a href="{{ $href }}" @if($active) aria-current="page" @endif
                                   class="group relative flex min-h-11 items-center gap-3 rounded-xl px-3 py-2 font-medium transition {{ $active ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50 hover:text-ink' }}">
                                    @if($active)<span class="absolute inset-y-2 left-0 w-1 rounded-full bg-signal"></span>@endif
                                    <x-site.icon :name="$icon" class="h-5 w-5 {{ $active ? 'text-brand-600' : 'text-slate-400 group-hover:text-slate-600' }}" />
                                    <span class="flex-1">{{ $label }}</span>
                                    @if($badge)
                                        <span class="rounded-full bg-accent-600 px-2 py-0.5 text-xs font-bold text-white" title="{{ $badge }} new">{{ $badge }}</span>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </nav>

            <div class="border-t border-slate-200 p-3">
                <a href="{{ route('home') }}" target="_blank" rel="noopener" class="flex min-h-11 items-center gap-3 rounded-xl px-3 text-sm font-medium text-slate-600 hover:bg-slate-50">
                    <x-site.icon name="globe" class="h-5 w-5 text-slate-400" /> View website
                </a>
            </div>
        </aside>

        {{-- Main --}}
        <div class="flex min-w-0 flex-1 flex-col">
            <header class="sticky top-0 z-30 flex items-center justify-between gap-4 border-b border-slate-200 bg-white/90 px-4 py-3 backdrop-blur sm:px-6">
                <div class="flex min-w-0 items-center gap-3">
                    <button type="button" @click="navOpen = true" class="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-slate-200 text-ink lg:hidden" aria-label="Open menu">
                        <x-site.icon name="menu" class="h-5 w-5" />
                    </button>
                    <h1 class="truncate text-lg font-bold text-ink">@yield('heading', __('Dashboard'))</h1>
                </div>

                <div class="relative" x-data="{ menu: false }" @click.outside="menu = false">
                    <button type="button" @click="menu = ! menu" :aria-expanded="menu" class="flex min-h-11 items-center gap-3 rounded-xl px-2 hover:bg-slate-50">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-signal text-sm font-bold text-white">{{ Str::upper(Str::substr($user->name, 0, 1)) }}</span>
                        <span class="hidden text-left leading-tight sm:block">
                            <span class="block text-sm font-semibold text-ink">{{ $user->name }}</span>
                            <span class="block text-xs text-slate-500">{{ $user->role_label }}</span>
                        </span>
                        <x-site.icon name="chevron-down" class="h-4 w-4 text-slate-400" />
                    </button>
                    <div x-show="menu" x-cloak x-transition.opacity.duration.150ms class="absolute right-0 mt-2 w-56 overflow-hidden rounded-2xl border border-slate-200 bg-white py-1 shadow-xl shadow-slate-900/10">
                        <a href="{{ route('profile.edit') }}" class="flex min-h-11 items-center gap-3 px-4 text-sm text-slate-700 hover:bg-slate-50"><x-site.icon name="users" class="h-4 w-4 text-slate-400" /> My profile & password</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex min-h-11 w-full items-center gap-3 px-4 text-sm text-slate-700 hover:bg-slate-50"><x-site.icon name="arrow-right" class="h-4 w-4 text-slate-400" /> Log out</button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                {{-- Breeze status keys (profile-updated…) are shown inline by the profile page instead --}}
                @if (session('status') && ! in_array(session('status'), ['profile-updated', 'password-updated', 'verification-link-sent'], true))
                    <div role="status" class="mb-6 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                        <x-site.icon name="check" class="mt-0.5 h-5 w-5 shrink-0" /> <span>{{ session('status') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div role="alert" class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                        <ul class="list-inside list-disc space-y-0.5">
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
