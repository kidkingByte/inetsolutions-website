@props(['title' => null, 'meta_description' => null])
@php
    // Pages pass these as named slots: <x-slot name="title"> / <x-slot name="meta_description">.
    $pageTitle = trim((string) $title) ?: site('site_title', config('app.name'));
    $description = trim((string) $meta_description) ?: site('meta_description');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>document.documentElement.classList.add('js')</script>

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $description }}">
    <meta name="theme-color" content="#040B18">
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ site('company_name') }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/logo-white.png') }}">
    <meta name="twitter:card" content="summary">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="site font-sans">
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[60] focus:rounded-full focus:bg-white focus:px-4 focus:py-2 focus:text-ink focus:shadow-lg">Skip to content</a>

    @include('site.partials.header')

    <main id="main">
        {{ $slot }}
    </main>

    @include('site.partials.footer')
    @include('site.partials.whatsapp')

    @stack('scripts')
</body>
</html>
