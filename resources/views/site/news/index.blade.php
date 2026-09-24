<x-site-layout>
    <x-slot name="title">News & Insights — {{ site('company_name') }}</x-slot>
    <x-site.page-banner title="Latest News & Insights" subtitle="Tips, technology and updates from INET SOLUTIONS LTD." />

    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('news') }}" class="rounded-full px-4 py-1.5 text-sm font-semibold {{ !$category ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-700' }}">All</a>
                @foreach($categories as $c)
                    <a href="{{ route('news', ['category' => $c]) }}" class="rounded-full px-4 py-1.5 text-sm font-semibold {{ $category === $c ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-700' }}">{{ $c }}</a>
                @endforeach
            </div>

            <div class="mt-10 grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($posts as $post)
                    <a href="{{ route('news.show', $post) }}" class="group flex flex-col rounded-2xl border border-slate-200 overflow-hidden hover:shadow-md transition">
                        @if($post->cover_image)
                            <img src="{{ asset('storage/'.$post->cover_image) }}" alt="" class="h-40 w-full object-cover" loading="lazy">
                        @endif
                        <div class="p-6 flex flex-col flex-1">
                            <span class="text-xs font-semibold uppercase text-accent">{{ $post->category }}</span>
                            <h3 class="mt-2 text-lg font-bold text-brand-950 group-hover:text-brand-700">{{ $post->title }}</h3>
                            <p class="mt-2 text-sm text-slate-600 flex-1">{{ $post->excerpt }}</p>
                            <span class="mt-4 text-xs text-slate-400">{{ optional($post->published_at)->format('M j, Y') }}</span>
                        </div>
                    </a>
                @empty
                    <p class="col-span-full text-center text-slate-500">No articles yet.</p>
                @endforelse
            </div>

            <div class="mt-12">{{ $posts->links() }}</div>
        </div>
    </section>
</x-site-layout>
