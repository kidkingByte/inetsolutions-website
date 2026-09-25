@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex items-center justify-center gap-2">
        @if ($paginator->onFirstPage())
            <span class="chip cursor-not-allowed opacity-40">← Previous</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="chip">← Previous</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-2 text-slate-500">{{ $element }}</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page" class="chip chip-active hidden sm:inline-flex">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="chip hidden sm:inline-flex">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="chip">Next →</a>
        @else
            <span class="chip cursor-not-allowed opacity-40">Next →</span>
        @endif
    </nav>
@endif
