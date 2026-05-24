@php
    $role = strtolower(str_replace(' ', '', auth()->user()->role ?? 'warga'));
    if (!function_exists('activeMobile')) {
        function activeMobile($route) {
            return request()->routeIs($route) 
                ? 'text-blue-600 dark:text-blue-400 font-bold' 
                : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-300';
        }
    }
@endphp

<!-- Mobile Bottom Navigation (Hidden on lg screens) -->
<div class="fixed bottom-0 inset-x-0 z-50 bg-white/90 dark:bg-slate-900/90 backdrop-blur-lg border-t border-slate-200 dark:border-slate-800 lg:hidden pb-safe">
    <div class="flex items-center justify-around h-16 px-2">
        
        <!-- DASHBOARD (Semua Role Punya) -->
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ activeMobile('dashboard') }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span class="text-[9px] uppercase tracking-wider">Beranda</span>
        </a>

        @if(in_array($role, ['warga']))
            <a href="{{ route('warga.profile') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ activeMobile('warga.profile') }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <span class="text-[9px] uppercase tracking-wider">Keluarga</span>
            </a>
            <a href="{{ route('warga.iuran') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ activeMobile('warga.iuran') }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span class="text-[9px] uppercase tracking-wider">Iuran</span>
            </a>
            <a href="{{ route('warga.agendas') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ activeMobile('warga.agendas') }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span class="text-[9px] uppercase tracking-wider">Agenda</span>
            </a>
            
            <form id="logout-form-mobile-warga" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
            <button onclick="document.getElementById('logout-form-mobile-warga').submit();" class="flex flex-col items-center justify-center w-full h-full space-y-1 text-rose-500 hover:text-rose-700 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                <span class="text-[9px] uppercase tracking-wider font-bold">Keluar</span>
            </button>

        @elseif(in_array($role, ['bendahara']))
            <a href="{{ route('tagihan.warga') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ activeMobile('tagihan.warga') }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                <span class="text-[9px] uppercase tracking-wider">Tagihan</span>
            </a>
            <a href="{{ route('verifikasi.index') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ activeMobile('verifikasi.index') }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-[9px] uppercase tracking-wider">Verifikasi</span>
            </a>
            <a href="{{ route('laporan.kas') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ activeMobile('laporan.kas') }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span class="text-[9px] uppercase tracking-wider">Laporan</span>
            </a>
            <button @click="bottomSheetOpen = true" class="flex flex-col items-center justify-center w-full h-full space-y-1 text-slate-400 hover:text-blue-600 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                <span class="text-[9px] uppercase tracking-wider">Lainnya</span>
            </button>

        @elseif(in_array($role, ['rt', 'superadmin']))
            <a href="{{ route('users.index') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ activeMobile('users.index') }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <span class="text-[9px] uppercase tracking-wider">Warga</span>
            </a>
            <a href="{{ route('agendas.index') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ activeMobile('agendas.index') }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span class="text-[9px] uppercase tracking-wider">Agenda</span>
            </a>
            <a href="{{ route('laporan.kas') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ activeMobile('laporan.kas') }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-[9px] uppercase tracking-wider">Keuangan</span>
            </a>
            <button @click="bottomSheetOpen = true" class="flex flex-col items-center justify-center w-full h-full space-y-1 text-slate-400 hover:text-blue-600 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                <span class="text-[9px] uppercase tracking-wider">Lainnya</span>
            </button>
        @endif

    </div>
    <!-- Safe Area Padding for iOS -->
    <div class="h-safe-area-bottom"></div>
</div>

<!-- Bottom Sheet Modal untuk "Lainnya" -->
<div x-show="bottomSheetOpen" x-cloak class="fixed inset-0 z-[60] lg:hidden">
    <!-- Overlay Transparan Hitam -->
    <div x-show="bottomSheetOpen" x-transition.opacity @click="bottomSheetOpen = false" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

    <!-- Kotak Menu yang Muncul dari Bawah -->
    <div x-show="bottomSheetOpen" 
         x-transition:enter="transition ease-out duration-300 transform" 
         x-transition:enter-start="translate-y-full" 
         x-transition:enter-end="translate-y-0" 
         x-transition:leave="transition ease-in duration-200 transform" 
         x-transition:leave-start="translate-y-0" 
         x-transition:leave-end="translate-y-full" 
         class="absolute bottom-0 w-full bg-white dark:bg-slate-900 rounded-t-[2rem] shadow-2xl flex flex-col pb-safe max-h-[85vh]">
        
        <!-- Handle/Garis Penarik di Atas Modal -->
        <div class="flex justify-center pt-4 pb-2 shrink-0">
            <div class="w-12 h-1.5 bg-slate-300 dark:bg-slate-700 rounded-full"></div>
        </div>
        
        <div class="px-6 pb-2 shrink-0">
            <h2 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest">Semua Menu</h2>
            <p class="text-[10px] text-slate-500 font-bold mt-1">Pilih layanan KAS-RT</p>
        </div>

        <div class="flex-1 overflow-y-auto px-6 py-4 custom-scrollbar">
            <!-- Grid Menu ala Bank/E-Commerce (4 Kolom) -->
            <div class="grid grid-cols-4 gap-y-6 gap-x-2">

                @if(in_array($role, ['rt', 'superadmin']))
                    <!-- Grup Administrasi -->
                    <div class="col-span-4 mt-2 mb-1"><h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Administrasi</h3></div>
                    
                    <a href="{{ route('users.index') }}" class="flex flex-col items-center gap-2 group">
                        <div class="size-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <span class="text-2xl">👥</span>
                        </div>
                        <span class="text-[9px] font-bold text-center leading-tight">Warga</span>
                    </a>
                    <a href="{{ route('statistik.warga') }}" class="flex flex-col items-center gap-2 group">
                        <div class="size-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <span class="text-2xl">📊</span>
                        </div>
                        <span class="text-[9px] font-bold text-center leading-tight">Statistik</span>
                    </a>
                    <a href="{{ route('agendas.index') }}" class="flex flex-col items-center gap-2 group">
                        <div class="size-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <span class="text-2xl">📅</span>
                        </div>
                        <span class="text-[9px] font-bold text-center leading-tight">Agenda</span>
                    </a>
                @endif

                @if(in_array($role, ['rt', 'bendahara', 'superadmin']))
                    <!-- Grup Keuangan -->
                    <div class="col-span-4 mt-4 mb-1"><h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Manajemen Kas</h3></div>
                    
                    <a href="{{ route('settings.payment') }}" class="flex flex-col items-center gap-2 group">
                        <div class="size-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                            <span class="text-2xl">💰</span>
                        </div>
                        <span class="text-[9px] font-bold text-center leading-tight">Metode<br>Bayar</span>
                    </a>
                    <a href="{{ route('iuran.master') }}" class="flex flex-col items-center gap-2 group">
                        <div class="size-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                            <span class="text-2xl">💳</span>
                        </div>
                        <span class="text-[9px] font-bold text-center leading-tight">Master<br>Iuran</span>
                    </a>
                    <a href="{{ route('tagihan.warga') }}" class="flex flex-col items-center gap-2 group">
                        <div class="size-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                            <span class="text-2xl">📋</span>
                        </div>
                        <span class="text-[9px] font-bold text-center leading-tight">Status<br>Tagihan</span>
                    </a>
                    <a href="{{ route('verifikasi.index') }}" class="flex flex-col items-center gap-2 group">
                        <div class="size-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                            <span class="text-2xl">✅</span>
                        </div>
                        <span class="text-[9px] font-bold text-center leading-tight">Verifikasi<br>Bayar</span>
                    </a>
                    <a href="{{ route('expenditures.index') }}" class="flex flex-col items-center gap-2 group">
                        <div class="size-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                            <span class="text-2xl">💸</span>
                        </div>
                        <span class="text-[9px] font-bold text-center leading-tight">Laporan<br>Kegiatan</span>
                    </a>
                    <a href="{{ route('laporan.kas') }}" class="flex flex-col items-center gap-2 group">
                        <div class="size-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                            <span class="text-2xl">📈</span>
                        </div>
                        <span class="text-[9px] font-bold text-center leading-tight">Laporan<br>Kas</span>
                    </a>
                @endif

                @if(in_array($role, ['rt', 'warga', 'superadmin']))
                    <!-- Grup Layanan Warga -->
                    <div class="col-span-4 mt-4 mb-1"><h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Layanan Pribadi</h3></div>
                    
                    <a href="{{ route('warga.profile') }}" class="flex flex-col items-center gap-2 group">
                        <div class="size-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center group-hover:bg-orange-600 group-hover:text-white transition-colors">
                            <span class="text-2xl">👨‍👩‍👧‍👦</span>
                        </div>
                        <span class="text-[9px] font-bold text-center leading-tight">Keluarga</span>
                    </a>
                    <a href="{{ route('warga.iuran') }}" class="flex flex-col items-center gap-2 group">
                        <div class="size-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center group-hover:bg-orange-600 group-hover:text-white transition-colors">
                            <span class="text-2xl">💵</span>
                        </div>
                        <span class="text-[9px] font-bold text-center leading-tight">Bayar<br>Iuran</span>
                    </a>
                    <a href="{{ route('warga.agendas') }}" class="flex flex-col items-center gap-2 group">
                        <div class="size-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center group-hover:bg-orange-600 group-hover:text-white transition-colors">
                            <span class="text-2xl">📅</span>
                        </div>
                        <span class="text-[9px] font-bold text-center leading-tight">Kegiatan<br>RT</span>
                    </a>
                @endif
                
                <!-- Grup Akun -->
                <div class="col-span-4 mt-4 mb-1"><h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Akun</h3></div>
                
                @if($role !== 'warga')
                <a href="{{ route('profile.edit') }}" class="col-span-1 flex flex-col items-center gap-2 group">
                    <div class="size-12 bg-slate-50 text-slate-600 rounded-2xl flex items-center justify-center group-hover:bg-slate-600 group-hover:text-white transition-colors">
                        <span class="text-2xl">👤</span>
                    </div>
                    <span class="text-[9px] font-bold text-center leading-tight">Profil</span>
                </a>
                @endif

                <form method="POST" action="{{ route('logout') }}" class="col-span-1">
                    @csrf
                    <button type="submit" class="flex flex-col items-center gap-2 group w-full">
                        <div class="size-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center group-hover:bg-rose-600 group-hover:text-white transition-colors">
                            <span class="text-2xl">🚪</span>
                        </div>
                        <span class="text-[9px] font-bold text-center text-rose-600 leading-tight">Keluar</span>
                    </button>
                </form>

            </div>
            <!-- Bottom padding for scrolling -->
            <div class="h-8"></div>
        </div>
    </div>
</div>

<style>
    /* Dukungan khusus environment safe area untuk iPhone X ke atas */
    @supports(padding-bottom: env(safe-area-inset-bottom)) {
        .pb-safe {
            padding-bottom: calc(env(safe-area-inset-bottom) + 0.5rem);
        }
    }
</style>
