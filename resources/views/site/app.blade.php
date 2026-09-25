@php
    $features = [
        ['users', 'View account'], ['card', 'Check internet package'], ['chart', 'Monitor usage'],
        ['document', 'View billing information'], ['wrench', 'Manage account'], ['bell', 'Receive notifications'],
        ['support', 'Access customer support'], ['signal', 'View service information'], ['wifi', 'Manage internet services'],
    ];
    // Real screens from the iNet App (public/images/app)
    $screens = [
        ['dashboard', 'Your connection at a glance', 'See your active package and speed, days remaining and next billing date — with Pay Bill, Invoice, Usage and Speed Test one tap away.', ['Subscription status', 'Weekly data usage', 'Quick actions'], 'iNet App home: active package, days remaining, pay bill, usage and speed test'],
        ['checkout', 'Renew in a few taps', 'Pick 1, 3, 6 or 12 months, see the total before you pay, and confirm with mobile money.', ['Flexible durations', 'Clear total before paying', 'Mobile money'], 'iNet App checkout: choose a duration and pay with mobile money'],
        ['splash', 'Built for INET customers', 'Sign in once and your account, bills and support are always with you.', ['Secure sign-in', 'Notifications', 'Customer support'], 'iNet App start screen'],
    ];
@endphp
<x-site-layout>
    <x-slot name="title">iNet Mobile App — {{ site('company_name') }}</x-slot>

    <x-site.page-banner eyebrow="iNet mobile app" title="Manage Your Internet From Your Phone" subtitle="Download the iNet App and manage your internet connection wherever you are.">
        <div class="mt-10 flex flex-wrap gap-3">
            @include('site.partials.store-badges')
        </div>
    </x-site.page-banner>

    {{-- Screen showcase --}}
    <section class="section">
        <div class="container-x">
            <x-site.section-heading eyebrow="Inside the app" title="Everything your account needs, in one app." />

            <div class="mt-20 space-y-24 lg:space-y-32">
                @foreach($screens as $i => [$screen, $title, $text, $points, $alt])
                    <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-20">
                        <div class="reveal relative mx-auto w-60 sm:w-64 {{ $i % 2 ? 'lg:order-2' : '' }}">
                            <x-site.signal-arcs :id="'screen-arcs-'.$i" class="pointer-events-none absolute -top-10 h-56 w-56 opacity-30 {{ $i % 2 ? '-right-20 -scale-x-100' : '-left-20' }}" />
                            <x-site.phone :screen="$screen" :alt="$alt" class="relative" :eager="$i === 0" />
                        </div>
                        <div class="reveal {{ $i % 2 ? 'lg:order-1' : '' }}">
                            <p class="eyebrow">{{ ['dashboard' => 'Dashboard', 'checkout' => 'Checkout', 'splash' => 'Welcome'][$screen] }}</p>
                            <h2 class="mt-4 text-3xl font-extrabold leading-tight sm:text-4xl">{{ $title }}</h2>
                            <p class="lead mt-5 max-w-lg">{{ $text }}</p>
                            <ul class="mt-8 flex flex-wrap gap-2">
                                @foreach($points as $point)
                                    <li class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700"><x-site.icon name="check" class="h-4 w-4 text-accent-600" /> {{ $point }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section-alt">
        <div class="container-x">
            <x-site.section-heading eyebrow="Features" title="Everything, in your pocket." />
            <ul class="mt-16 grid gap-5 sm:grid-cols-2 lg:grid-cols-3" data-stagger>
                @foreach($features as [$icon, $label])
                    <li class="card flex items-center gap-4 !py-5">
                        <span class="icon-tile shrink-0"><x-site.icon :name="$icon" class="h-6 w-6" /></span>
                        <span class="font-semibold text-ink">{{ $label }}</span>
                    </li>
                @endforeach
            </ul>
            <div class="mt-12 flex flex-wrap justify-center gap-3">
                @include('site.partials.store-badges')
            </div>
        </div>
    </section>

    @include('site.partials.cta-banner')
</x-site-layout>
