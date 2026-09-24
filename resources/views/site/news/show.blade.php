<x-site-layout>
    <x-slot name="title">{{ $post->meta_title ?: $post->title }} — {{ site('company_name') }}</x-slot>
    <x-slot name="meta_description">{{ $post->meta_description ?: $post->excerpt }}</x-slot>

    <article class="py-16 bg-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <span class="text-xs font-semibold uppercase text-accent">{{ $post->category }}</span>
            <h1 class="mt-2 text-3xl sm:text-4xl font-extrabold tracking-tight text-brand-950">{{ $post->title }}</h1>
            <p class="mt-3 text-sm text-slate-500">{{ $post->author }} · {{ optional($post->published_at)->format('M j, Y') }}</p>
            @if($post->cover_image)
                <img src="{{ asset('storage/'.$post->cover_image) }}" alt="" class="mt-8 rounded-2xl w-full object-cover">
            @endif
            <div class="mt-8 space-y-4 text-slate-700 leading-relaxed">
                @foreach(preg_split('/\n\s*\n/', e($post->body)) as $para)
                    <p>{!! nl2br($para) !!}</p>
                @endforeach
            </div>
            <div class="mt-10 border-t border-slate-100 pt-6">
                <a href="{{ route('news') }}" class="text-sm font-semibold text-brand-700">← Back to News</a>
            </div>
        </div>

        @if($related->isNotEmpty())
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mt-16">
                <h2 class="text-xl font-bold text-brand-950">Related articles</h2>
                <div class="mt-6 grid sm:grid-cols-3 gap-6">
                    @foreach($related as $r)
                        <a href="{{ route('news.show', $r) }}" class="rounded-2xl border border-slate-200 p-5 hover:shadow-md transition">
                            <h3 class="font-semibold text-brand-950">{{ $r->title }}</h3>
                            <p class="mt-2 text-sm text-slate-600">{{ $r->excerpt }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </article>
</x-site-layout>
