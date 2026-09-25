<a href="{{ route('news.show', $post) }}" class="card card-hover reveal group flex flex-col overflow-hidden !p-0">
    <div class="relative aspect-[16/9] overflow-hidden bg-gradient-to-br from-brand-50 via-white to-accent-50">
        @if($post->cover_image)
            <img src="{{ asset('storage/'.$post->cover_image) }}" alt="" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
        @else
            <x-site.icon name="signal" class="absolute bottom-5 right-5 h-16 w-16 text-ink/10" />
        @endif
        @if($post->category)
            <span class="absolute left-4 top-4 rounded-full border border-slate-200 bg-white/90 px-3 py-1 text-xs font-semibold text-brand-600 backdrop-blur">{{ $post->category }}</span>
        @endif
    </div>
    <div class="flex flex-1 flex-col p-6">
        <p class="text-xs text-slate-500">{{ optional($post->published_at)->format('M j, Y') }}</p>
        <h3 class="mt-2 text-lg font-bold leading-snug transition group-hover:text-brand-700">{{ $post->title }}</h3>
        <p class="mt-3 flex-1 text-sm leading-relaxed text-slate-500">{{ $post->excerpt }}</p>
        <span class="link-arrow mt-6">Read article <x-site.icon name="arrow-right" class="h-4 w-4" /></span>
    </div>
</a>
