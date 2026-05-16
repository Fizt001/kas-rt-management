<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="text-xl font-black text-slate-800 dark:text-white tracking-tight leading-tight uppercase">
                Infaq <span class="text-emerald-600">Digital</span>
            </h2>
            <p class="text-[11px] text-slate-500 font-medium uppercase tracking-widest mt-1">Zakat, Infaq & Sedekah Warga</p>
        </div>
    </x-slot>

    {{-- WRAPPER X-DATA UTAMA --}}
    <div x-data="{ 
            showModalInfaq: false, 
            showModalQRIS: false,
            copyToClipboard(text) {
                navigator.clipboard.writeText(text);
                alert('Nomor Rekening berhasil disalin!');
            }
        }" 
        class="p-4 sm:p-6 max-w-7xl mx-auto space-y-6">
        
        {{-- 1. CARD GRADIENT UTAMA (Sama dengan Style Koperasi) --}}
        <div class="bg-gradient-to-br from-emerald-500 to-teal-700 rounded-[2.5rem] shadow-xl shadow-emerald-500/20 p-8 relative overflow-hidden flex flex-col sm:flex-row items-center justify-between gap-6">
            <svg class="absolute -right-4 -top-4 opacity-10 size-40 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            
            <div class="relative z-10 w-full sm:w-auto text-center sm:text-left">
                <p class="text-[10px] font-black text-emerald-100 uppercase tracking-widest mb-1">
                    {{ $isAdmin ? 'Total Amal Jariyah Warga' : 'Total Amal Jariyah Saya' }}
                </p>
                <h1 class="text-4xl font-black text-white tracking-tighter italic">
                    <span class="text-emerald-200 text-xl mr-1">Rp</span>{{ number_format($totalInfaqTarget, 0, ',', '.') }}
                </h1>
                
                @if($isAdmin && request('warga_id'))
                    <p class="text-[10px] font-bold text-emerald-100 mt-2 bg-white/10 px-3 py-1 rounded-full inline-block uppercase tracking-wider italic">
                        An. {{ $targetUser->name }}
                    </p>
                @endif
            </div>

            {{-- DROPDOWN MONITORING (Hanya Admin) --}}
            @if($isAdmin)
                <div class="relative z-20 w-full sm:w-auto flex flex-col items-center sm:items-end gap-3">
                    <form action="{{ route('warga.infaq') }}" method="GET" class="w-full sm:w-auto">
                        <p class="text-[9px] font-black text-emerald-100 uppercase tracking-[0.2em] mb-2 text-center sm:text-right">Monitoring Infaq:</p>
                        <select name="warga_id" onchange="this.form.submit()" 
                            class="bg-white/10 hover:bg-white/20 text-white border border-white/20 rounded-2xl py-2 px-5 text-xs font-black focus:ring-2 focus:ring-white cursor-pointer outline-none w-full sm:min-w-[240px] backdrop-blur-md transition-all">
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

            {{-- Tombol Tunaikan (Hanya untuk Warga / Diri Sendiri) --}}
            @if(auth()->user()->role == 'warga' && !request('warga_id'))
                <button @click="showModalInfaq = true" class="relative z-20 w-full sm:w-auto px-8 py-4 bg-white text-emerald-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-50 transition-all shadow-2xl active:scale-95 flex items-center justify-center gap-2">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                    Tunaikan Infaq
                </button>
            @endif
        </div>

        <div class="grid lg:grid-cols-10 gap-6">
            
            {{-- 2. AREA KIRI: RIWAYAT (7 Kolom) --}}
            <div class="lg:col-span-7 bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-800 p-6">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-50 dark:border-slate-800">
                    <h3 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest">
                        Riwayat Transaksi
                    </h3>
                    <span class="text-[9px] font-bold text-slate-400 italic">Total {{ $infaqs->count() }} Record</span>
                </div>

                <div class="space-y-3">
                    @forelse($infaqs as $amal)
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50/50 dark:bg-slate-800/30 border border-slate-50 dark:border-slate-800 group transition-all hover:bg-white dark:hover:bg-slate-800 shadow-sm hover:shadow-md">
                            <div>
                                <p class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-tight">{{ $amal->kategori }}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase italic">{{ $amal->created_at->format('d M Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-black text-slate-800 dark:text-white">
                                    Rp{{ number_format($amal->nominal, 0, ',', '.') }}
                                </p>
                                <span class="text-[8px] font-black uppercase px-2 py-0.5 rounded-md {{ $amal->status == 'terverifikasi' ? 'text-emerald-500 bg-emerald-50' : 'text-amber-500 bg-amber-50' }}">
                                    {{ $amal->status == 'terverifikasi' ? 'Berhasil' : 'Menunggu' }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <p class="text-[10px] font-bold text-slate-300 uppercase tracking-[0.2em] italic">Belum Ada Riwayat Transaksi</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- 3. AREA KANAN: TUJUAN TRANSFER (3 Kolom) --}}
            <div class="lg:col-span-3 space-y-6">
                <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-800 p-6">
                    <h3 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest mb-6 border-b border-slate-50 dark:border-slate-800 pb-4 text-center">Tujuan Transfer</h3>
                    
                    <div class="space-y-4 text-center">
                        {{-- Info Rekening --}}
                        <div class="p-5 bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl border border-emerald-100 dark:border-emerald-800 relative group">
                            <p class="text-[8px] font-black text-emerald-600 uppercase tracking-widest mb-1">{{ $setting->nama_bank ?? 'Bank' }}</p>
                            <h4 class="text-base font-black text-slate-800 dark:text-white tracking-tight">{{ $setting->nomor_rekening ?? '-' }}</h4>
                            <p class="text-[10px] font-bold text-slate-500 mt-1 uppercase">A.N {{ $setting->nama_penerima ?? '-' }}</p>
                            
                            <button type="button" @click="copyToClipboard('{{ $setting->nomor_rekening ?? '' }}')" class="absolute right-3 top-1/2 -translate-y-1/2 p-2 bg-white dark:bg-slate-700 shadow-sm rounded-xl opacity-0 group-hover:opacity-100 transition-all text-emerald-600">
                                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            </button>
                        </div>

                        {{-- Barcode QRIS --}}
                        <div class="p-2">
                            <button type="button" @click="showModalQRIS = true" class="relative group cursor-pointer inline-block p-4 bg-white dark:bg-slate-800 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-[2.5rem] transition-all hover:border-emerald-400 focus:outline-none">
                                @if(isset($setting) && $setting->qris_path)
                                    <img src="{{ asset('storage/' . $setting->qris_path) }}" class="w-32 h-32 object-cover rounded-2xl shadow-sm" alt="QRIS">
                                @else
                                    <div class="w-32 h-32 flex items-center justify-center text-slate-300">
                                        <svg class="size-12" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z"/></svg>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-emerald-600/10 opacity-0 group-hover:opacity-100 rounded-[2.5rem] flex items-center justify-center transition-all">
                                    <div class="bg-white/80 p-2 rounded-full shadow-lg">
                                        <svg class="size-6 text-emerald-600" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196zM10.5 7.5v6m3-3h-6"/></svg>
                                    </div>
                                </div>
                            </button>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-4">Klik Barcode untuk Zoom</p>
                        </div>
                    </div>
                </div>

                {{-- INFO MODE AUDITOR (Jika monitoring) --}}
                @if($targetUser->id != auth()->id())
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-[2.5rem] border border-blue-100 dark:border-blue-800 text-center">
                        <div class="size-12 bg-blue-100 dark:bg-blue-900/50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-inner">
                            <svg class="size-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <h4 class="text-xs font-black text-blue-700 dark:text-blue-400 uppercase tracking-widest">Audit Mode</h4>
                        <p class="text-[9px] font-bold text-slate-500 mt-2 italic leading-relaxed">
                            Memantau riwayat infaq <b>{{ $targetUser->name }}</b>.
                        </p>
                    </div>
                @endif
            </div>
        </div>

        {{-- MODAL FORM INFAQ (Tetap di dalam scope x-data) --}}
        <div x-show="showModalInfaq" style="display: none;" class="fixed inset-0 z-[110] overflow-y-auto" x-cloak>
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showModalInfaq = false"></div>
            <div class="flex items-center justify-center min-h-screen p-4 relative">
                <div x-show="showModalInfaq" x-transition class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem] w-full max-w-sm p-8 shadow-2xl text-left">
                    <h3 class="text-lg font-black text-slate-800 dark:text-white tracking-tight mb-6 italic">Form <span class="text-emerald-500">Tunaikan Infaq</span></h3>
                    <form action="{{ route('warga.infaq.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Tujuan Amal</label>
                            <select name="kategori" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl py-3.5 px-4 text-xs font-bold focus:ring-2 focus:ring-emerald-500 dark:text-white shadow-sm">
                                <option value="Mesjid">Kas Mesjid</option>
                                <option value="Anak Yatim">Santunan Anak Yatim</option>
                                <option value="Sosial">Bantuan Sosial</option>
                                <option value="Jumat Berkah">Jumat Berkah</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Nominal (Rp)</label>
                            <input type="number" name="nominal" required min="1000" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl py-3.5 px-4 text-xs font-bold focus:ring-2 focus:ring-emerald-500 dark:text-white shadow-sm" placeholder="50000">
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Bukti Transfer</label>
                            <input type="file" name="bukti_transfer" accept="image/*" required class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl py-2 px-3 text-[10px] font-bold dark:text-white file:bg-emerald-500 file:text-white file:border-0 file:rounded-xl file:px-4 file:py-2 shadow-sm">
                        </div>
                        <div class="pt-4 flex gap-3">
                            <button type="button" @click="showModalInfaq = false" class="flex-1 py-4 bg-slate-100 dark:bg-slate-800 text-slate-500 rounded-2xl font-black text-[10px] uppercase tracking-widest">Batal</button>
                            <button type="submit" class="flex-[2] py-4 bg-emerald-600 text-white rounded-2xl font-black text-[10px] uppercase shadow-lg active:scale-95 transition-all">Kirim Amal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL QRIS ZOOM (Tetap di dalam scope x-data) --}}
        <div x-show="showModalQRIS" style="display: none;" class="fixed inset-0 z-[150] overflow-y-auto" x-cloak>
            <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-md" @click="showModalQRIS = false"></div>
            <div class="flex items-center justify-center min-h-screen p-4 text-center z-10 relative">
                <div x-show="showModalQRIS" x-transition class="relative max-w-sm w-full">
                    <div class="bg-white p-4 rounded-[2.5rem] shadow-2xl">
                        @if(isset($setting) && $setting->qris_path)
                            <img src="{{ asset('storage/' . $setting->qris_path) }}" class="w-full h-auto rounded-2xl shadow-sm" alt="QRIS Zoom">
                        @endif
                        <div class="mt-4 text-center">
                            <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Scan QRIS Mesjid</h3>
                            <button @click="showModalQRIS = false" class="mt-6 w-full py-3 bg-emerald-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest active:scale-95 transition-all">Tutup Gambar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div> {{-- AKHIR WRAPPER X-DATA --}}
</x-app-layout>