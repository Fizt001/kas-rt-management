<x-app-layout>
    @php
        $userRole = strtolower(str_replace(' ', '', auth()->user()->role ?? 'warga'));
        $isAdminMode = in_array($userRole, ['superadmin', 'rt']);
        $isMember = isset($akun) && $akun != null;
    @endphp

    @if($isAdminMode || $isMember)
        <x-slot name="header">
            <div class="flex flex-col">
                <h2 class="text-xl font-black text-slate-800 dark:text-white tracking-tight leading-tight uppercase">
                    Tabungan <span class="text-amber-500">Koperasi</span>
                </h2>
                <p class="text-[11px] text-slate-500 font-medium uppercase tracking-widest mt-1">Sistem Simpan Pinjam Lingkungan</p>
            </div>
        </x-slot>

        {{-- WRAPPER UTAMA --}}
        <div x-data="{ 
            showModalQRIS: false,
            copyToClipboard(text) {
                navigator.clipboard.writeText(text);
                alert('Nomor Rekening Koperasi berhasil disalin!');
            }
        }" class="py-6 px-4 sm:px-6 max-w-7xl mx-auto space-y-6">
            
            {{-- 1. CARD GRADIENT TOTAL SALDO --}}
            <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-[2.5rem] shadow-xl shadow-amber-500/20 p-8 relative overflow-hidden flex flex-col sm:flex-row items-center justify-between gap-6">
                <svg class="absolute -right-4 -top-4 opacity-10 size-40 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                
                <div class="relative z-10 w-full sm:w-auto text-center sm:text-left">
                    <p class="text-[10px] font-black text-amber-100 uppercase tracking-widest mb-1">Total Saldo Terkumpul</p>
                    <h1 class="text-4xl font-black text-white tracking-tighter italic">
                        <span class="text-amber-200 text-xl mr-1">Rp</span>{{ number_format($akun->total_saldo ?? 0, 0, ',', '.') }}
                    </h1>
                    @if($targetUser->id != auth()->id())
                        <p class="text-[10px] font-bold text-amber-100 mt-2 bg-white/10 px-3 py-1 rounded-full inline-block uppercase tracking-wider italic">An. {{ $targetUser->name }}</p>
                    @endif
                </div>

                {{-- DROPDOWN MONITORING --}}
                @if($isAdminMode)
                    <div class="relative z-20 w-full sm:w-auto flex flex-col items-center sm:items-end">
                        <form action="{{ url()->current() }}" method="GET" class="w-full sm:w-auto">
                            <p class="text-[9px] font-black text-amber-100 uppercase tracking-[0.2em] mb-2 text-center sm:text-right">Monitoring Anggota:</p>
                            <select name="warga_id" onchange="this.form.submit()" class="bg-white/10 hover:bg-white/20 text-white border border-white/20 rounded-2xl py-2 px-5 text-xs font-black focus:ring-2 focus:ring-white cursor-pointer outline-none w-full sm:min-w-[240px] backdrop-blur-md transition-all">
                                <option value="{{ auth()->id() }}" class="text-slate-800">Tampilkan Data Saya</option>
                                @foreach($daftarWarga as $w)
                                    <option value="{{ $w->id }}" {{ request('warga_id') == $w->id ? 'selected' : '' }} class="text-slate-800">
                                        Warga: {{ $w->name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                @endif
            </div>

            {{-- 2. GRID 3 CARD RINCIAN --}}
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-white dark:bg-slate-900 p-5 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm flex flex-col items-center sm:items-start">
                    <p class="text-[9px] font-black uppercase text-slate-400 mb-1">Simpanan Pokok</p>
                    <h3 class="text-lg font-black dark:text-white italic tracking-tight">Rp{{ number_format($akun->saldo_pokok ?? 0, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-white dark:bg-slate-900 p-5 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm flex flex-col items-center sm:items-start">
                    <p class="text-[9px] font-black uppercase text-slate-400 mb-1">Simpanan Wajib</p>
                    <h3 class="text-lg font-black dark:text-white italic tracking-tight">Rp{{ number_format($akun->saldo_wajib ?? 0, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-white dark:bg-slate-900 p-5 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm flex flex-col items-center sm:items-start">
                    <p class="text-[9px] font-black uppercase text-slate-400 mb-1">Simpanan Sukarela</p>
                    <h3 class="text-lg font-black dark:text-white italic tracking-tight">Rp{{ number_format($akun->saldo_sukarela ?? 0, 0, ',', '.') }}</h3>
                </div>
            </div>

            {{-- 3. GRID UTAMA: RIWAYAT & TUJUAN TRANSFER --}}
            <div class="grid lg:grid-cols-10 gap-6">
                
                {{-- AREA KIRI: RIWAYAT (7 Kolom) --}}
                <div class="lg:col-span-7 bg-white dark:bg-slate-900 rounded-[2.5rem] p-6 shadow-sm border border-slate-100 dark:border-slate-800">
                    <h3 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest mb-6 border-b pb-4">Riwayat Transaksi</h3>
                    <div class="space-y-3">
                        @forelse($riwayat as $trx)
                            <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50/50 dark:bg-slate-800/30 border border-slate-50 dark:border-slate-800">
                                <div>
                                    <p class="text-xs font-black uppercase dark:text-white tracking-tight">{{ $trx->kategori }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase italic">{{ $trx->created_at->format('d M Y') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-black {{ $trx->type == 'setoran' ? 'text-emerald-500' : 'text-rose-500' }}">
                                        {{ $trx->type == 'setoran' ? '+' : '-' }} Rp{{ number_format($trx->amount, 0, ',', '.') }}
                                    </p>
                                    <span class="text-[8px] font-black uppercase {{ $trx->status == 'approved' ? 'text-emerald-500' : 'text-amber-500' }}">
                                        {{ $trx->status }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10"><p class="text-[10px] font-bold text-slate-300 uppercase tracking-[0.2em]">Belum Ada Data</p></div>
                        @endforelse
                    </div>
                </div>

                {{-- AREA KANAN: TUJUAN TRANSFER (3 Kolom) --}}
                <div class="lg:col-span-3 space-y-6">
                    
                    {{-- INFO REKENING --}}
                    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-6 shadow-sm border border-slate-100 dark:border-slate-800">
                        <h3 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest mb-6 border-b border-slate-50 dark:border-slate-800 pb-4 text-center">Tujuan Setoran</h3>
                        <div class="space-y-4 text-center">
                            <div class="p-5 bg-amber-50 dark:bg-amber-900/20 rounded-2xl border border-amber-100 dark:border-amber-800 relative group">
                                <p class="text-[8px] font-black text-amber-600 uppercase mb-1">{{ $setting->nama_bank ?? 'Bank Koperasi' }}</p>
                                <h4 class="text-base font-black text-slate-800 dark:text-white">{{ $setting->nomor_rekening ?? '-' }}</h4>
                                <p class="text-[10px] font-bold text-slate-500 mt-1 uppercase text-center">A.N {{ $setting->atas_nama ?? '-' }}</p>
                                
                                <button type="button" @click="copyToClipboard('{{ $setting->nomor_rekening ?? '' }}')" class="absolute right-3 top-1/2 -translate-y-1/2 p-2 bg-white dark:bg-slate-700 shadow-sm rounded-xl opacity-0 group-hover:opacity-100 transition-all text-amber-600">
                                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                </button>
                            </div>

                            {{-- QRIS --}}
                            <div class="p-2">
                                <button type="button" @click="showModalQRIS = true" class="relative group cursor-pointer inline-block p-4 bg-white dark:bg-slate-800 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-[2.5rem] transition-all hover:border-amber-400 focus:outline-none">
                                    @if(isset($setting) && $setting->qris_image)
                                        <img src="{{ asset('storage/' . $setting->qris_image) }}" class="w-32 h-32 object-cover rounded-2xl shadow-sm" alt="QRIS">
                                    @else
                                        <div class="w-32 h-32 flex items-center justify-center text-slate-300">
                                            <svg class="size-12" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5z"/></svg>
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-amber-600/10 opacity-0 group-hover:opacity-100 rounded-[2.5rem] flex items-center justify-center transition-all">
                                        <div class="bg-white/80 p-2 rounded-full shadow-lg">
                                            <svg class="size-6 text-amber-600" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196zM10.5 7.5v6m3-3h-6"/></svg>
                                        </div>
                                    </div>
                                </button>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-4">Klik Barcode untuk Zoom</p>
                            </div>
                        </div>
                    </div>

                    {{-- PESAN AUDITOR (Hanya untuk Admin) --}}
                    @if($targetUser->id != auth()->id())
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-[2.5rem] border border-blue-100 text-center">
                            <h4 class="text-xs font-black text-blue-700 dark:text-blue-400 uppercase tracking-widest">Audit Mode</h4>
                            <p class="text-[9px] font-bold text-slate-500 mt-2 italic leading-relaxed">
                                Anda sedang memantau data {{ $targetUser->name }}.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- MODAL QRIS ZOOM (Harus di dalam x-data) --}}
            <div x-show="showModalQRIS" style="display: none;" class="fixed inset-0 z-[150] overflow-y-auto" x-cloak>
                <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-md" @click="showModalQRIS = false"></div>
                <div class="flex items-center justify-center min-h-screen p-4 text-center z-10 relative">
                    <div x-show="showModalQRIS" x-transition class="relative max-w-sm w-full">
                        <div class="bg-white p-4 rounded-[2.5rem] shadow-2xl">
                            @if(isset($setting) && $setting->qris_image)
                                <img src="{{ asset('storage/' . $setting->qris_image) }}" class="w-full h-auto rounded-2xl" alt="QRIS Zoom">
                            @endif
                            <div class="mt-4 text-center">
                                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">QRIS Koperasi RT</h3>
                                <button @click="showModalQRIS = false" class="mt-6 w-full py-3 bg-amber-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest active:scale-95 transition-all">Tutup Gambar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div> {{-- AKHIR WRAPPER X-DATA --}}

    @else
        {{-- PORTAL PENDAFTARAN --}}
        <x-slot name="header">
            <h2 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">Pendaftaran <span class="text-amber-500">Koperasi</span></h2>
        </x-slot>
        <div class="py-10 px-4 sm:px-6 max-w-4xl mx-auto">
             <div class="bg-white dark:bg-slate-900 rounded-[3rem] p-12 shadow-2xl border border-slate-100 dark:border-slate-800 text-center">
                <h3 class="text-2xl font-black mb-4 uppercase">Maju Bersama Lingkungan!</h3>
                <form action="{{ route('warga.koperasi.join') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-12 py-5 bg-amber-500 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl">
                        Ya, Saya Ingin Bergabung
                    </button>
                </form>
            </div>
        </div>
    @endif
</x-app-layout>