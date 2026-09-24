@php $filters = ['' => 'All Plans', 'home' => 'Home', 'business' => 'Business', 'enterprise' => 'Enterprise']; @endphp
<x-site-layout>
    <x-slot name="title">Internet Packages — {{ site('company_name') }}</x-slot>
    <x-site.page-banner title="Choose Your Internet Plan" subtitle="Choose an internet package that matches your connectivity needs." />

    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap justify-center gap-2">
                @foreach($filters as $key => $label)
                    <a href="{{ route('packages', $key ? ['category' => $key] : []) }}" class="rounded-full px-4 py-2 text-sm font-semibold {{ ($category ?: '') === $key ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">{{ $label }}</a>
                @endforeach
            </div>

            <div class="mt-12 grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($packages as $package)
                    <x-site.package-card :package="$package" />
                @empty
                    <p class="col-span-full text-center text-slate-500">No packages in this category yet.</p>
                @endforelse
            </div>

            <p class="mt-10 text-center text-sm text-slate-500">All prices are shown in TZS. Fair usage policy and installation fees may apply. <a href="{{ route('contact') }}" class="text-brand-700 font-semibold">Ask us</a> for the latest offers.</p>
        </div>
    </section>

    @include('site.partials.cta-banner')
</x-site-layout>
