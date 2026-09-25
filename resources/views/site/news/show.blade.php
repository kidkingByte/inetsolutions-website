<x-site-layout>
    <x-slot name="title">{{ $post->meta_title ?: $post->title }} — {{ site('company_name') }}</x-slot>
    <x-slot name="meta_description">{{ $post->meta_description ?: $post->excerpt }}</x-slot>

    <article>
        <header class="relative overflow-hidden border-b border-slate-100 pb-14 pt-36 sm:pt-44">
            <div class="pointer-events-none absolute inset-0 bg-wash"></div>
            <div class="pointer-events-none absolute -top-24 right-0 h-96 w-96 rounded-full bg-brand-100/70 blur-3xl"></div>
            <div class="container-x relative max-w-3xl">
                <a href="{{ route('news') }}" class="link-arrow">← All articles</a>
                @if($post->category)
                    <p class="eyebrow mt-8">{{ $post->category }}</p>
                @endif
                <h1 class="mt-5 text-4xl font-extrabold leading-tight sm:text-5xl">{{ $post->title }}</h1>
                @if($post->excerpt)
                    <p class="lead mt-6">{{ $post->excerpt }}</p>
                @endif
                <p class="mt-8 flex items-center gap-3 text-sm text-slate-500">
                    <span class="font-medium text-slate-600">{{ $post->author }}</span>
                    <span class="h-1 w-1 rounded-full bg-slate-600"></span>
                    <time datetime="{{ optional($post->published_at)->toDateString() }}">{{ optional($post->published_at)->format('F j, Y') }}</time>
                </p>
            </div>
        </header>

        <div class="container-x max-w-3xl py-14">
            @if($post->cover_image)
                <img src="{{ asset('storage/'.$post->cover_image) }}" alt="" class="mb-12 w-full rounded-3xl border border-slate-200 object-cover">
            @endif
            <div class="prose-dark text-lg">
                @foreach(preg_split('/\n\s*\n/', e($post->body)) as $para)
                    <p>{!! nl2br($para) !!}</p>
                @endforeach
            </div>

            <div class="card mt-16 flex flex-col items-start justify-between gap-5 sm:flex-row sm:items-center">
                <p class="font-semibold text-ink">Need reliable internet for your home or business?</p>
                <a href="{{ route('get-connected') }}" class="btn-primary shrink-0">Get Connected</a>
            </div>
        </div>
    </article>

    @if($related->isNotEmpty())
        <section class="section section-alt">
            <div class="container-x">
                <h2 class="text-2xl font-bold">Related articles</h2>
                <div class="mt-8 grid gap-6 md:grid-cols-3">
                    @foreach($related as $r)
                        @include('site.news._card', ['post' => $r])
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-site-layout>
