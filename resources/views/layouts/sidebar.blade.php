@php
    if (!function_exists('active')) {
        function active($route) {
            return request()->routeIs($route)
                ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50'
                : 'text-slate-600 hover:bg-slate-200 hover:text-blue-700';
        }
    }
    $role = strtolower(str_replace(' ', '', auth()->user()->role ?? 'warga'));
@endphp

{{-- BAGIAN 1: HEADER LOGO (Tetap di Atas) --}}
<div class="px-6 h-16 flex items-center shrink-0 border-b border-slate-200 bg-slate-100 transition-all duration-300" :class="sidebarMini ? 'justify-center px-2' : 'justify-between lg:justify-start'">
    <div class="flex flex-col overflow-hidden" x-show="!sidebarMini" x-transition.opacity>
        <span class="text-xl font-black text-blue-600 uppercase tracking-tighter">KAS-RT</span>
        <p class="text-[9px] text-slate-500 font-bold tracking-[0.2em] uppercase mt-0.5">Management</p>
    </div>
    
    <div class="flex items-center" :class="sidebarMini ? '' : 'ml-auto'">
        <button @click="sidebarMini = !sidebarMini" class="hidden lg:block p-2 text-slate-400 hover:text-blue-600 hover:bg-slate-200 rounded-lg transition-all duration-300" :class="sidebarMini ? 'rotate-180' : ''">
            <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/></svg>
        </button>
    </div>

    <button @click="sidebarOpen = false" class="lg:hidden p-2 text-slate-400 hover:text-blue-600" x-show="!sidebarMini">
        <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
</div>

{{-- BAGIAN 2: MENU NAVIGASI (Hanya ini yang bisa scroll) --}}
<nav class="flex-1 h-0 overflow-y-auto custom-scrollbar bg-slate-100 py-6 transition-all duration-300" :class="sidebarMini ? 'px-2' : 'px-4'">
        <ul role="list" class="flex flex-col gap-y-7">
            <li>
                <ul role="list" class="space-y-1">
                    <li>
                        <a href="{{ route('dashboard') }}" :title="sidebarMini ? 'Dashboard' : ''" class="group flex items-center py-2.5 px-4 text-sm font-bold rounded-2xl transition-all duration-300 {{ active('dashboard') }}" :class="sidebarMini ? 'justify-center px-0' : 'gap-x-3'">
                            <span class="text-xl shrink-0">📊</span>
                            <span x-show="!sidebarMini" class="whitespace-nowrap transition-all">Dashboard</span>
                        </a>
                    </li>
                    
                    {{-- ================= ADMINISTRASI RT ================= --}}
                    @if(in_array($role, ['rt', 'superadmin']))
                        <li class="pt-6 pb-1 text-[10px] font-black text-slate-600 uppercase tracking-[0.2em]" :class="sidebarMini ? 'text-center pl-0' : 'pl-4'"><span x-show="!sidebarMini">Administrasi</span><span x-show="sidebarMini">...</span></li>
                        <li><a href="{{ route('users.index') }}" :title="sidebarMini ? 'Manajemen Warga' : ''" class="group flex items-center py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('users.index') }}" :class="sidebarMini ? 'justify-center px-0' : 'gap-x-3'"><span class="text-xl shrink-0">👥</span> <span x-show="!sidebarMini" class="whitespace-nowrap transition-all">Manajemen Warga</span></a></li>
                        <li><a href="{{ route('agendas.index') }}" :title="sidebarMini ? 'Agenda Kegiatan' : ''" class="group flex items-center py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('agendas.index') }}" :class="sidebarMini ? 'justify-center px-0' : 'gap-x-3'"><span class="text-xl shrink-0">📅</span> <span x-show="!sidebarMini" class="whitespace-nowrap transition-all">Agenda Kegiatan</span></a></li>
                    @endif
                    
                    {{-- ================= MANAJEMEN KAS ================= --}}
                    @if(in_array($role, ['rt', 'bendahara', 'superadmin']))
                        <li class="pt-5 pb-1 text-[10px] font-black text-slate-600 uppercase tracking-[0.2em]" :class="sidebarMini ? 'text-center pl-0' : 'pl-4'"><span x-show="!sidebarMini">Manajemen Kas</span><span x-show="sidebarMini">...</span></li>
                        @if(in_array($role, ['rt', 'bendahara', 'superadmin']))
                            <li><a href="{{ route('settings.payment') }}" :title="sidebarMini ? 'Metode Bayar' : ''" class="group flex items-center py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('settings.payment') }}" :class="sidebarMini ? 'justify-center px-0' : 'gap-x-3'"><span class="text-xl shrink-0">💰</span><span x-show="!sidebarMini" class="whitespace-nowrap transition-all">Metode Bayar</span></a></li>
                        @endif
                        <li><a href="{{ route('iuran.master') }}" :title="sidebarMini ? 'Master Iuran' : ''" class="group flex items-center py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('iuran.master') }}" :class="sidebarMini ? 'justify-center px-0' : 'gap-x-3'"><span class="text-xl shrink-0">💳</span> <span x-show="!sidebarMini" class="whitespace-nowrap transition-all">Master Iuran</span></a></li>
                        <li><a href="{{ route('tagihan.warga') }}" :title="sidebarMini ? 'Status Tagihan' : ''" class="group flex items-center py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('tagihan.warga') }}" :class="sidebarMini ? 'justify-center px-0' : 'gap-x-3'"><span class="text-xl shrink-0">📋</span> <span x-show="!sidebarMini" class="whitespace-nowrap transition-all">Status Tagihan</span></a></li>
                        <li><a href="{{ route('verifikasi.index') }}" :title="sidebarMini ? 'Verifikasi Bayaran' : ''" class="group flex items-center py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('verifikasi.index') }}" :class="sidebarMini ? 'justify-center px-0' : 'gap-x-3'"><span class="text-xl shrink-0">✅</span> <span x-show="!sidebarMini" class="whitespace-nowrap transition-all">Verifikasi Bayaran</span></a></li>
                        <li><a href="{{ route('expenditures.index') }}" :title="sidebarMini ? 'Penggunaan Dana' : ''" class="group flex items-center py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('expenditures.index') }}" :class="sidebarMini ? 'justify-center px-0' : 'gap-x-3'"><span class="text-xl shrink-0">💸</span> <span x-show="!sidebarMini" class="whitespace-nowrap transition-all">Penggunaan Dana</span></a></li>
                        <li><a href="{{ route('laporan.kas') }}" :title="sidebarMini ? 'Laporan Kas' : ''" class="group flex items-center py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('laporan.kas') }}" :class="sidebarMini ? 'justify-center px-0' : 'gap-x-3'"><span class="text-xl shrink-0">📈</span> <span x-show="!sidebarMini" class="whitespace-nowrap transition-all">Laporan Kas</span></a></li>
                    @endif

                    {{-- ================= PENGURUS MESJID ================= --}}
                    @if(in_array($role, ['mesjid', 'superadmin']))
                        <li class="pt-6 pb-1 font-bold text-[10px] text-emerald-600 uppercase tracking-widest border-t border-slate-200 mt-4" :class="sidebarMini ? 'text-center pl-0' : 'pl-4'"><span x-show="!sidebarMini">Pengurus Mesjid</span><span x-show="sidebarMini">...</span></li>
                        <li><a href="{{ route('mesjid.payment') }}" :title="sidebarMini ? 'Metode Bayar' : ''" class="group flex items-center py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('mesjid.payment') }}" :class="sidebarMini ? 'justify-center px-0' : 'gap-x-3'"><span class="text-xl shrink-0">🕌</span> <span x-show="!sidebarMini" class="whitespace-nowrap transition-all">Metode Bayar</span></a></li>
                        <li><a href="{{ route('mesjid.pengeluaran') }}" :title="sidebarMini ? 'Penggunaan Dana' : ''" class="group flex items-center py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('mesjid.pengeluaran') }}" :class="sidebarMini ? 'justify-center px-0' : 'gap-x-3'"><span class="text-xl shrink-0">💸</span> <span x-show="!sidebarMini" class="whitespace-nowrap transition-all">Penggunaan Dana</span></a></li>
                        <li><a href="{{ route('mesjid.laporan') }}" :title="sidebarMini ? 'Laporan Kas' : ''" class="group flex items-center py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('mesjid.laporan') }}" :class="sidebarMini ? 'justify-center px-0' : 'gap-x-3'"><span class="text-xl shrink-0">📑</span> <span x-show="!sidebarMini" class="whitespace-nowrap transition-all">Laporan Kas</span></a></li>
                    @endif

                    {{-- ================= PENGURUS KOPERASI ================= --}}
                    @if(in_array($role, ['koperasi', 'superadmin']))
                        <li class="pt-6 pb-1 font-bold text-[10px] text-amber-600 uppercase tracking-widest border-t border-slate-200 mt-4" :class="sidebarMini ? 'text-center pl-0' : 'pl-4'"><span x-show="!sidebarMini">Pengurus Koperasi</span><span x-show="sidebarMini">...</span></li>
                        <li><a href="{{ route('koperasi.admin.payment') }}" :title="sidebarMini ? 'Metode Bayar' : ''" class="group flex items-center py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('koperasi.admin.payment') }}" :class="sidebarMini ? 'justify-center px-0' : 'gap-x-3'"><span class="text-xl shrink-0">💰</span> <span x-show="!sidebarMini" class="whitespace-nowrap transition-all">Metode Bayar</span></a></li>
                        <li><a href="{{ route('koperasi.admin.anggota') }}" :title="sidebarMini ? 'Data Anggota' : ''" class="group flex items-center py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('koperasi.admin.anggota') }}" :class="sidebarMini ? 'justify-center px-0' : 'gap-x-3'"><span class="text-xl shrink-0">👥</span> <span x-show="!sidebarMini" class="whitespace-nowrap transition-all">Data Anggota</span></a></li>
                        <li><a href="{{ route('koperasi.admin.transaksi') }}" :title="sidebarMini ? 'Data Transaksi' : ''" class="group flex items-center py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('koperasi.admin.transaksi') }}" :class="sidebarMini ? 'justify-center px-0' : 'gap-x-3'"><span class="text-xl shrink-0">💸</span> <span x-show="!sidebarMini" class="whitespace-nowrap transition-all">Data Transaksi</span></a></li>
                        <li><a href="{{ route('koperasi.admin.kasbon') }}" :title="sidebarMini ? 'Data Kasbon' : ''" class="group flex items-center py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('koperasi.admin.kasbon') }}" :class="sidebarMini ? 'justify-center px-0' : 'gap-x-3'"><span class="text-xl shrink-0">🧾</span> <span x-show="!sidebarMini" class="whitespace-nowrap transition-all">Data Kasbon</span></a></li>
                        <li><a href="{{ route('koperasi.laporan.kas') }}" :title="sidebarMini ? 'Laporan Kas' : ''" class="group flex items-center py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('koperasi.laporan.kas') }}" :class="sidebarMini ? 'justify-center px-0' : 'gap-x-3'"><span class="text-xl shrink-0">📊</span> <span x-show="!sidebarMini" class="whitespace-nowrap transition-all">Laporan Kas</span></a></li>
                    @endif

                    {{-- ================= LAYANAN WARGA ================= --}}
                    @if(in_array($role, ['warga', 'superadmin']))
                        <li class="pt-6 pb-1 font-bold text-[10px] text-slate-500 uppercase tracking-widest border-t border-slate-200 mt-4" :class="sidebarMini ? 'text-center pl-0' : 'pl-4'"><span x-show="!sidebarMini">Layanan Warga</span><span x-show="sidebarMini">...</span></li>
                        <li><a href="{{ route('warga.profile') }}" :title="sidebarMini ? 'Data Keluarga' : ''" class="group flex items-center py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('warga.profile') }}" :class="sidebarMini ? 'justify-center px-0' : 'gap-x-3'"><span class="text-xl shrink-0">👨‍👩‍👧‍👦</span> <span x-show="!sidebarMini" class="whitespace-nowrap transition-all">Data Keluarga</span></a></li>
                        <li><a href="{{ route('warga.iuran') }}" :title="sidebarMini ? 'Iuran & Bayar' : ''" class="group flex items-center py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('warga.iuran') }}" :class="sidebarMini ? 'justify-center px-0' : 'gap-x-3'"><span class="text-xl shrink-0">💵</span> <span x-show="!sidebarMini" class="whitespace-nowrap transition-all">Iuran & Bayar</span></a></li>
                        <li><a href="{{ route('warga.infaq') }}" :title="sidebarMini ? 'Tunaikan Infaq' : ''" class="group flex items-center py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('warga.infaq') }}" :class="sidebarMini ? 'justify-center px-0' : 'gap-x-3'"><span class="text-xl shrink-0">🤲</span> <span x-show="!sidebarMini" class="whitespace-nowrap transition-all">Tunaikan Infaq</span></a></li>
                        <li><a href="{{ route('warga.agendas') }}" :title="sidebarMini ? 'Kegiatan RT' : ''" class="group flex items-center py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('warga.agendas') }}" :class="sidebarMini ? 'justify-center px-0' : 'gap-x-3'"><span class="text-xl shrink-0">📅</span> <span x-show="!sidebarMini" class="whitespace-nowrap transition-all">Kegiatan RT</span></a></li>
                        <li><a href="{{ route('warga.koperasi') }}" :title="sidebarMini ? 'Tabungan Koperasi' : ''" class="group flex items-center py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('warga.koperasi') }}" :class="sidebarMini ? 'justify-center px-0' : 'gap-x-3'"><span class="text-xl shrink-0">🏦</span> <span x-show="!sidebarMini" class="whitespace-nowrap transition-all">Tabungan Koperasi</span></a></li>
                    @endif
                </ul>
            </li>
        </ul>
    </nav>

{{-- BAGIAN 3: FOOTER LOGOUT (Tetap di Bawah) --}}
<div class="p-4 border-t border-slate-200 bg-slate-100 shrink-0 transition-all duration-300">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" :title="sidebarMini ? 'Keluar Aplikasi' : ''" class="group flex items-center rounded-xl p-3 text-sm font-bold text-rose-400 hover:bg-rose-500/10 w-full text-left transition-all shadow-sm" :class="sidebarMini ? 'justify-center px-0' : 'gap-x-3'">
            <span class="text-xl shrink-0">🚪</span> 
            <span x-show="!sidebarMini" class="whitespace-nowrap transition-all">Keluar Aplikasi</span>
        </button>
    </form>
</div>