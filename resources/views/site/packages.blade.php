@php $filters = ['' => 'All Plans', 'home' => 'Home', 'business' => 'Business', 'enterprise' => 'Enterprise']; @endphp
<x-site-layout>
    <x-slot name="title">Internet Packages — {{ site('company_name') }}</x-slot>

    <x-site.page-banner eyebrow="Packages" title="Choose Your Internet Plan" subtitle="Choose an internet package that matches your connectivity needs.">
        <nav class="mt-10 flex flex-wrap gap-2" aria-label="Package categories">
            @foreach($filters as $key => $label)
                <a href="{{ route('packages', $key ? ['category' => $key] : []) }}" class="chip {{ ($category ?: '') === $key ? 'chip-active' : '' }}">{{ $label }}</a>
            @endforeach
        </nav>
    </x-site.page-banner>

    <section class="section">
        <div class="container-x">
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3" data-stagger>
                @forelse($packages as $package)
                    <x-site.package-card :package="$package" />
                @empty
                    <p class="col-span-full text-center text-slate-500">No packages in this category yet.</p>
                @endforelse
            </div>

            <div class="card reveal mt-14 flex flex-col items-start justify-between gap-6 sm:flex-row sm:items-center">
                <div class="flex items-start gap-4">
                    <span class="icon-tile shrink-0"><x-site.icon name="document" class="h-6 w-6" /></span>
                    <div>
                        <p class="font-semibold text-ink">Good to know</p>
                        <p class="mt-1 text-sm text-slate-500">All prices are shown in TZS. Fair usage policy and installation fees may apply. Business and enterprise customers can request customized packages.</p>
                    </div>
                </div>
                <a href="{{ route('contact') }}" class="btn-secondary shrink-0">Ask our team</a>
            </div>
        </div>
    </section>

    @include('site.partials.cta-banner')
</x-site-layout>
