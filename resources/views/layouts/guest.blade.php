<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="robots" content="noindex">

        <title>Staff sign in — {{ site('company_name', config('app.name')) }}</title>
        <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="grid min-h-screen lg:grid-cols-2">
            {{-- Brand panel --}}
            <div class="relative hidden overflow-hidden bg-night lg:flex lg:flex-col lg:justify-between lg:p-12">
                <div class="pointer-events-none absolute inset-0 bg-grid mask-fade-b"></div>
                <div class="pointer-events-none absolute -left-24 -top-24 h-96 w-96 rounded-full bg-brand-600/30 blur-3xl"></div>
                <div class="pointer-events-none absolute -bottom-32 right-0 h-96 w-96 rounded-full bg-signal-violet/25 blur-3xl"></div>

                <a href="{{ route('home') }}" class="relative"><img src="{{ asset('images/logo-mark-white.png') }}" alt="{{ site('company_name') }}" class="h-14 w-auto"></a>
                <div class="relative">
                    <p class="text-4xl font-extrabold leading-tight text-white">{{ site('tagline') }}</p>
                    <p class="mt-4 max-w-md text-slate-400">{{ site('positioning') }}</p>
                </div>
                <p class="relative text-sm text-slate-500">&copy; {{ date('Y') }} {{ site('company_name') }}</p>
            </div>

            {{-- Form --}}
            <div class="flex flex-col items-center justify-center bg-brand-light px-6 py-12">
                <a href="{{ route('home') }}" class="mb-8 rounded-2xl bg-night p-3 lg:hidden"><img src="{{ asset('images/logo-mark-white.png') }}" alt="{{ site('company_name') }}" class="h-12 w-auto"></a>
                <div class="w-full max-w-md">
                    <h1 class="text-2xl font-extrabold text-ink">Staff sign in</h1>
                    <p class="mt-1 text-sm text-slate-500">Sign in to the INET management panel.</p>
                    <div class="mt-8 rounded-2xl bg-white p-8 shadow-xl shadow-slate-200/60 ring-1 ring-slate-200">
                        {{ $slot }}
                    </div>
                    <p class="mt-6 text-center text-sm text-slate-500"><a href="{{ route('home') }}" class="font-semibold text-brand-700 hover:text-brand-800">← Back to website</a></p>
                </div>
            </div>
        </div>
    </body>
</html>
