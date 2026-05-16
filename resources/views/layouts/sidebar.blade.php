@php
    if (!function_exists('active')) {
        function active($route) {
            return request()->routeIs($route)
                ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50'
                : 'text-slate-400 hover:bg-slate-900 hover:text-white';
        }
    }
    $role = strtolower(str_replace(' ', '', auth()->user()->role ?? 'warga'));
@endphp

{{-- BAGIAN 1: HEADER LOGO (Tetap di Atas) --}}
<div class="px-6 h-16 flex items-center shrink-0 border-b border-slate-800 bg-slate-950 justify-between lg:justify-start">
    <div class="flex flex-col">
        <span class="text-xl font-black text-blue-500 uppercase tracking-tighter">KAS-RT</span>
        <p class="text-[9px] text-slate-500 font-bold tracking-[0.2em] uppercase mt-0.5">Management</p>
    </div>
    <button @click="sidebarOpen = false" class="lg:hidden p-2 text-slate-400 hover:text-white">
        <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
</div>

{{-- BAGIAN 2: MENU NAVIGASI (Hanya ini yang bisa scroll) --}}
<nav class="flex-1 h-0 overflow-y-auto custom-scrollbar bg-slate-950 px-4 py-6">
        <ul role="list" class="flex flex-col gap-y-7">
            <li>
                <ul role="list" class="space-y-1">
                    <li>
                        <a href="{{ route('dashboard') }}" class="group flex items-center gap-x-3 py-2.5 px-4 text-sm font-bold rounded-2xl transition-all duration-300 {{ active('dashboard') }}">
                            📊 Dashboard
                        </a>
                    </li>
                    {{-- ================= ADMINISTRASI RT ================= --}}
                    @if(in_array($role, ['rt', 'superadmin']))
                        <li class="pt-6 pb-1 pl-4 text-[10px] font-black text-slate-600 uppercase tracking-[0.2em]">Administrasi</li>
                        <li><a href="{{ route('users.index') }}" class="group flex items-center gap-x-3 py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('users.index') }}">👥 Manajemen Warga</a></li>
                        <li><a href="{{ route('agendas.index') }}" class="group flex items-center gap-x-3 py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('agendas.index') }}">📅 Agenda Kegiatan</a></li>
                    @endif
                    
                    {{-- ================= MANAJEMEN KAS ================= --}}
                    @if(in_array($role, ['rt', 'bendahara', 'superadmin']))
                        <li class="pt-5 pb-1 pl-4 text-[10px] font-black text-slate-600 uppercase tracking-[0.2em]">Manajemen Kas</li>
                        @if(in_array($role, ['rt', 'bendahara', 'superadmin']))
                            <li><a href="{{ route('settings.payment') }}" class="group flex items-center gap-x-3 py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('settings.payment') }}">💰Metode Bayar</a></li>
                        @endif
                        <li><a href="{{ route('iuran.master') }}" class="group flex items-center gap-x-3 py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('iuran.master') }}">💳 Master Iuran</a></li>
                        <li><a href="{{ route('tagihan.warga') }}" class="group flex items-center gap-x-3 py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('tagihan.warga') }}">📋 Status Tagihan</a></li>
                        <li><a href="{{ route('verifikasi.index') }}" class="group flex items-center gap-x-3 py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('verifikasi.index') }}">✅ Verifikasi Bayaran</a></li>
                        <li><a href="{{ route('expenditures.index') }}" class="group flex items-center gap-x-3 py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('expenditures.index') }}">💸 Penggunaan Dana</a></li>
                        <li><a href="{{ route('laporan.kas') }}" class="group flex items-center gap-x-3 py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('laporan.kas') }}">📈 Laporan Kas</a></li>
                    @endif

                    

                    {{-- ================= PENGURUS MESJID ================= --}}
                    @if(in_array($role, ['mesjid', 'superadmin']))
                        <li class="pt-6 pb-1 pl-4 font-bold text-[10px] text-emerald-600 uppercase tracking-widest border-t border-slate-800 mt-4">Pengurus Mesjid</li>
                        <li><a href="{{ route('mesjid.payment') }}" class="group flex items-center gap-x-3 py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('mesjid.payment') }}">🕌 Metode Bayar</a></li>
                        <li><a href="{{ route('mesjid.pengeluaran') }}" class="group flex items-center gap-x-3 py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('mesjid.pengeluaran') }}">💸 Penggunaan Dana</a></li>
                        <li><a href="{{ route('mesjid.laporan') }}" class="group flex items-center gap-x-3 py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('mesjid.laporan') }}">📑 Laporan Kas</a></li>
                    @endif

                    {{-- ================= PENGURUS KOPERASI (YANG HILANG) ================= --}}
                    @if(in_array($role, ['koperasi', 'superadmin']))
                        <li class="pt-6 pb-1 pl-4 font-bold text-[10px] text-amber-500 uppercase tracking-widest border-t border-slate-800 mt-4">Pengurus Koperasi</li>
                        <li><a href="{{ route('koperasi.admin.payment') }}" class="group flex items-center gap-x-3 py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('koperasi.admin.payment') }}">💰 Metode Bayar</a></li>
                        <li><a href="{{ route('koperasi.admin.anggota') }}" class="group flex items-center gap-x-3 py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('koperasi.admin.anggota') }}">👥 Data Anggota</a></li>
                        <li><a href="{{ route('koperasi.admin.transaksi') }}" class="group flex items-center gap-x-3 py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('koperasi.admin.transaksi') }}">💸 Data Transaksi</a></li>
                        <li><a href="{{ route('koperasi.admin.kasbon') }}" class="group flex items-center gap-x-3 py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('koperasi.admin.kasbon') }}">🧾 Data Kasbon</a></li>
                        <li><a href="{{ route('koperasi.laporan.kas') }}" class="group flex items-center gap-x-3 py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('koperasi.laporan.kas') }}">📊 Laporan Kas</a></li>
                    @endif

                    {{-- ================= LAYANAN WARGA ================= --}}
                    @if(in_array($role, ['warga', 'superadmin']))
                        <li class="pt-6 pb-1 pl-4 font-bold text-[10px] text-slate-500 uppercase tracking-widest border-t border-slate-800 mt-4">Layanan Warga</li>
                        <li><a href="{{ route('warga.iuran') }}" class="group flex items-center gap-x-3 py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('warga.iuran') }}">💵 Iuran & Bayar</a></li>
                        <li><a href="{{ route('warga.infaq') }}" class="group flex items-center gap-x-3 py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('warga.infaq') }}">🤲 Tunaikan Infaq</a></li>
                        {{-- Menu Tabungan Koperasi untuk Warga --}}
                        <li><a href="{{ route('warga.koperasi') }}" class="group flex items-center gap-x-3 py-2 px-4 text-sm font-medium rounded-xl transition-all {{ active('warga.koperasi') }}">🏦 Tabungan Koperasi</a></li>
                    @endif
                </ul>
            </li>
        </ul>
    </nav>

{{-- BAGIAN 3: FOOTER LOGOUT (Tetap di Bawah) --}}
<div class="p-4 border-t border-slate-800 bg-slate-950 shrink-0">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="group flex items-center gap-x-3 rounded-xl p-3 text-sm font-bold text-rose-400 hover:bg-rose-500/10 w-full text-left transition-all shadow-sm">
            <span>🚪</span> Keluar Aplikasi
        </button>
    </form>
</div>