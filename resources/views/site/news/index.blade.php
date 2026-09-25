<x-site-layout>
    <x-slot name="title">News & Insights — {{ site('company_name') }}</x-slot>

    <x-site.page-banner eyebrow="Insights" title="Latest News & Insights" subtitle="Tips, technology and updates from INET SOLUTIONS LTD.">
        <div class="mt-10 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <nav class="flex flex-wrap gap-2" aria-label="Categories">
                <a href="{{ route('news') }}" class="chip {{ ! $category ? 'chip-active' : '' }}">All</a>
                @foreach($categories as $c)
                    <a href="{{ route('news', ['category' => $c]) }}" class="chip {{ $category === $c ? 'chip-active' : '' }}">{{ $c }}</a>
                @endforeach
            </nav>
            <form method="GET" action="{{ route('news') }}" class="relative w-full lg:w-72" role="search">
                @if($category)<input type="hidden" name="category" value="{{ $category }}">@endif
                <label for="news-q" class="sr-only">Search articles</label>
                <x-site.icon name="search" class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" />
                <input id="news-q" name="q" value="{{ request('q') }}" placeholder="Search articles…" class="field rounded-full pl-11">
            </form>
        </div>
    </x-site.page-banner>

    <section class="section">
        <div class="container-x">
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3" data-stagger>
                @forelse($posts as $post)
                    @include('site.news._card', ['post' => $post])
                @empty
                    <p class="col-span-full text-center text-slate-500">No articles found.</p>
                @endforelse
            </div>

            <div class="mt-14">{{ $posts->links('site.partials.pagination') }}</div>
        </div>
    </section>
</x-site-layout>
