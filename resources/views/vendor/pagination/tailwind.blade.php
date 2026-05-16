@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
        
        {{-- TAMPILAN MOBILE (Hanya Tombol Prev/Next) --}}
        <div class="flex flex-1 gap-2 justify-between sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-xs font-black uppercase tracking-widest text-slate-400 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 cursor-not-allowed rounded-xl shadow-sm">
                    &laquo; Prev
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-xs font-black uppercase tracking-widest text-blue-600 bg-white dark:bg-slate-800 border border-blue-200 dark:border-slate-700 rounded-xl hover:bg-blue-50 transition shadow-sm active:scale-95">
                    &laquo; Prev
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-xs font-black uppercase tracking-widest text-blue-600 bg-white dark:bg-slate-800 border border-blue-200 dark:border-slate-700 rounded-xl hover:bg-blue-50 transition shadow-sm active:scale-95">
                    Next &raquo;
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 text-xs font-black uppercase tracking-widest text-slate-400 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 cursor-not-allowed rounded-xl shadow-sm">
                    Next &raquo;
                </span>
            @endif
        </div>

        {{-- TAMPILAN DESKTOP (Lengkap dengan Nomor Halaman) --}}
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest leading-5">
                    Menampilkan 
                    <span class="font-black text-slate-800 dark:text-slate-200">{{ $paginator->firstItem() }}</span>
                    sampai
                    <span class="font-black text-slate-800 dark:text-slate-200">{{ $paginator->lastItem() }}</span>
                    dari
                    <span class="font-black text-slate-800 dark:text-slate-200">{{ $paginator->total() }}</span>
                    data
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex shadow-sm rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700">
                    
                    {{-- Tombol Panah Kiri --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true">
                            <span class="relative inline-flex items-center px-2 py-2 bg-slate-50 dark:bg-slate-800 text-slate-300 cursor-not-allowed" aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-2 py-2 bg-white dark:bg-slate-800 text-slate-500 hover:text-blue-600 transition" aria-label="Previous">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                        </a>
                    @endif

                    {{-- Elemen Nomor Halaman --}}
                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="relative inline-flex items-center px-4 py-2 -ml-px text-xs font-black text-slate-400 bg-white dark:bg-slate-800 border-l border-slate-200 dark:border-slate-700 cursor-default">{{ $element }}</span>
                            </span>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="relative inline-flex items-center px-4 py-2 -ml-px text-xs font-black text-white bg-blue-600 border-l border-slate-200 dark:border-slate-700 cursor-default">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 -ml-px text-xs font-black text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border-l border-slate-200 dark:border-slate-700 hover:bg-slate-50 transition" aria-label="Halaman {{ $page }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Tombol Panah Kanan --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-2 py-2 -ml-px bg-white dark:bg-slate-800 text-slate-500 hover:text-blue-600 transition" aria-label="Next">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                        </a>
                    @else
                        <span aria-disabled="true">
                            <span class="relative inline-flex items-center px-2 py-2 -ml-px bg-slate-50 dark:bg-slate-800 text-slate-300 cursor-not-allowed" aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif