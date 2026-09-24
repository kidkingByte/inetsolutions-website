<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', site('site_title', config('app.name')))</title>
    <meta name="description" content="@yield('meta_description', site('meta_description'))">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-ink bg-white antialiased">
    @include('site.partials.header')

    <main>
        {{ $slot }}
    </main>

    @include('site.partials.footer')
    @include('site.partials.whatsapp')

    @stack('scripts')
</body>
</html>
