@if ($paginator->hasPages())
    <nav class="mt-14 flex items-center justify-between border-t border-line pt-6" aria-label="Pagination">
        @if ($paginator->onFirstPage())
            <span class="meta opacity-40">← Newer</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="btn-line">← Newer</a>
        @endif
        <span class="meta">Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}</span>
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="btn-line">Older →</a>
        @else
            <span class="meta opacity-40">Older →</span>
        @endif
    </nav>
@endif
