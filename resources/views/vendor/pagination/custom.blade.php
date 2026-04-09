@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center gap-1.5">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-300 bg-slate-50 cursor-not-allowed border border-slate-100 transition-all duration-200" aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 bg-white hover:text-brand-600 hover:bg-brand-50 border border-slate-200 hover:border-brand-200 transition-all duration-200 active:scale-90" aria-label="{{ __('pagination.previous') }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="inline-flex items-center justify-center w-8 h-8 text-slate-300 text-xs font-bold" aria-disabled="true">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-brand-600 text-white text-xs font-bold shadow-md shadow-brand-200 ring-2 ring-brand-100 transition-all duration-300 pointer-events-none" aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-500 bg-white text-xs font-bold border border-slate-200 hover:text-brand-600 hover:bg-brand-50 hover:border-brand-200 transition-all duration-200 active:scale-90" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 bg-white hover:text-brand-600 hover:bg-brand-50 border border-slate-200 hover:border-brand-200 transition-all duration-200 active:scale-90" aria-label="{{ __('pagination.next') }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @else
            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-300 bg-slate-50 cursor-not-allowed border border-slate-100 transition-all duration-200" aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </span>
        @endif
    </nav>
@endif
