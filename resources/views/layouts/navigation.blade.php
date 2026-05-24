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

            <div class="relative inline-flex">
                <div class="size-9 rounded-xl border-2 border-slate-100 overflow-hidden">
                    <img class="size-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=2563eb&color=fff&bold=true">
                </div>
            </div>
        </div>
    </nav>
</header>
