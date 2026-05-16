{{-- Perhatikan: Saya hapus lg:ps-64 karena di app.blade.php pembungkusnya sudah punya lg:pl-64 --}}
<header class="sticky top-0 z-[60] w-full bg-white/80 backdrop-blur-md border-b border-slate-200 py-3 dark:bg-slate-900/80 dark:border-gray-800 transition-all">
    {{-- Container Nav: Tanpa max-w agar mepet ke kiri --}}
    <nav class="flex items-center justify-between w-full px-4 sm:px-6 lg:px-8" aria-label="Global">
        
        <div class="flex items-center gap-4">
            <div class="lg:hidden shrink-0">
                <a class="text-xl font-black text-blue-600 tracking-tighter" href="{{ route('dashboard') }}">KAS-RT</a>
            </div>

            <div class="flex flex-col items-start text-left leading-none">
                @isset($header)
                    {{-- Area ini diisi oleh <x-slot name="header"> --}}
                    {{ $header }}
                @else
                    <h2 class="text-sm font-black text-slate-800 dark:text-white tracking-tight uppercase">
                        Dashboard
                    </h2>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">
                        Ringkasan Sistem
                    </p>
                @endisset
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="hidden sm:flex flex-col items-end leading-none">
                <span class="text-[11px] font-black text-slate-700 dark:text-slate-200 uppercase">
                    {{ Auth::user()->name }}
                </span>
                <span class="text-[9px] font-bold text-blue-500 uppercase tracking-widest mt-1">
                    {{ Auth::user()->role ?? 'Super Admin' }}
                </span>
            </div>

            <div class="relative inline-flex" x-data="{ profileOpen: false }" @click.away="profileOpen = false">
                <button @click="profileOpen = !profileOpen" type="button" class="size-9 rounded-xl border-2 border-slate-100 overflow-hidden hover:border-blue-500 transition-all focus:outline-none">
                    <img class="size-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=2563eb&color=fff&bold=true">
                </button>

                <div x-show="profileOpen" x-cloak
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="absolute right-0 top-11 min-w-[200px] bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xl rounded-2xl p-2 z-[100]">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-x-3 py-2.5 px-3 rounded-xl text-xs font-bold text-rose-600 hover:bg-rose-50 transition-colors">
                            <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
</header>

<div class="sticky top-[61px] inset-x-0 z-40 bg-white/80 backdrop-blur-md border-b px-4 lg:hidden py-2 dark:bg-slate-900/80">
    <div class="flex items-center">
        <button @click="sidebarOpen = true" class="p-2 text-slate-500 hover:bg-slate-100 rounded-xl focus:outline-none">
            <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
        </button>
        <span class="ms-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Panel Kontrol</span>
    </div>
</div>