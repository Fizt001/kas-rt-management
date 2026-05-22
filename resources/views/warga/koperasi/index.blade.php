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
            showModalSetor: false,
            showModalTarik: false,
            showModalKasbon: false,
            showModalCicilan: false,
            cicilanId: null,
            nominalCicilan: 0,
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

            {{-- MENU AKSI TRANSAKSI KHUSUS WARGA --}}
            @if($targetUser->id == auth()->id())
            <div class="flex flex-wrap justify-center sm:justify-start gap-3 mt-2">
                <button @click="showModalSetor = true" class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-md shadow-emerald-500/20 active:scale-95 transition-all">
                    + Setor Saldo
                </button>
                <button @click="showModalTarik = true" class="px-5 py-2.5 bg-rose-500 hover:bg-rose-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-md shadow-rose-500/20 active:scale-95 transition-all">
                    - Tarik Dana
                </button>
                @if(!$pinjamanAktif)
                <button @click="showModalKasbon = true" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-md shadow-amber-500/20 active:scale-95 transition-all">
                    💸 Ajukan Kasbon
                </button>
                @endif
            </div>
            @endif

            {{-- JIKA PUNYA PINJAMAN AKTIF --}}
            @if($pinjamanAktif)
                <div class="bg-rose-50 dark:bg-rose-900/20 rounded-[2rem] p-6 border border-rose-100 dark:border-rose-800 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-rose-500 mb-1">Pinjaman / Kasbon Aktif</p>
                        <h3 class="text-xl font-black text-rose-700 dark:text-rose-400">Rp{{ number_format($pinjamanAktif->amount, 0, ',', '.') }}</h3>
                        <p class="text-[10px] font-bold text-slate-500 mt-1 uppercase">{{ $pinjamanAktif->alasan }} (Tenor: {{ $pinjamanAktif->tenor }} bln)</p>
                    </div>
                    <div class="text-center md:text-right">
                        @if($pinjamanAktif->status == 'pending')
                            <span class="bg-amber-100 text-amber-600 px-4 py-2 rounded-xl text-[10px] font-black uppercase border border-amber-200">Menunggu Persetujuan Pengurus</span>
                        @else
                            @php
                                $cicilanBulanIni = $pinjamanAktif->installments()->where('status', 'belum_bayar')->first();
                                $totalTerbayar = $pinjamanAktif->installments()->where('status', 'lunas')->sum('amount');
                                $sisa = $pinjamanAktif->amount - $totalTerbayar;
                            @endphp
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Sisa Tagihan: Rp{{ number_format($sisa, 0, ',', '.') }}</p>
                            @if($cicilanBulanIni && $targetUser->id == auth()->id())
                                <div class="flex flex-col gap-2 md:items-end">
                                    <button @click="cicilanId = {{ $cicilanBulanIni->id }}; nominalCicilan = {{ $cicilanBulanIni->amount }}; showModalCicilan = true" class="bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2 rounded-xl text-[10px] font-black uppercase shadow-md active:scale-95 transition-all">Upload Bukti Transfer</button>
                                    
                                    @if(isset($akun) && $akun->saldo_sukarela >= $cicilanBulanIni->amount)
                                    <form action="{{ route('warga.koperasi.cicilan.saldo', $cicilanBulanIni->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin memotong Saldo Sukarela sebesar Rp{{ number_format($cicilanBulanIni->amount, 0, ',', '.') }} untuk membayar cicilan ini?')">
                                        @csrf
                                        <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white px-5 py-2 rounded-xl text-[10px] font-black uppercase shadow-md active:scale-95 transition-all flex items-center justify-center gap-1">
                                            <span>Bayar via Saldo Sukarela</span>
                                        </button>
                                    </form>
                                    @else
                                    <p class="text-[8px] font-bold text-rose-500 mt-1">*Saldo Sukarela tidak cukup untuk potong otomatis</p>
                                    @endif
                                </div>
                            @else
                                <span class="text-[10px] font-black text-emerald-600 uppercase border-b border-emerald-300">Cicilan Lunas / Sedang Diverifikasi</span>
                            @endif
                        @endif
                    </div>
                </div>
            @endif

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

            {{-- MODAL SETOR SALDO --}}
            <div x-show="showModalSetor" style="display: none;" class="fixed inset-0 z-[150] overflow-y-auto" x-cloak>
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showModalSetor = false"></div>
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div x-show="showModalSetor" class="relative bg-white dark:bg-slate-900 shadow-2xl rounded-[2.5rem] w-full max-w-sm p-6 text-left">
                        <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight mb-4">Setor <span class="text-emerald-500">Saldo</span></h3>
                        <form action="{{ route('warga.koperasi.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Jenis Simpanan</label>
                                <select name="kategori" required class="w-full bg-slate-50 border-none rounded-xl text-sm font-bold focus:ring-2 focus:ring-emerald-500 text-slate-800">
                                    <option value="wajib">Simpanan Wajib</option>
                                    <option value="sukarela">Simpanan Sukarela</option>
                                    <option value="pokok">Simpanan Pokok</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Nominal (Min Rp 10.000)</label>
                                <input type="number" name="amount" required min="10000" class="w-full bg-slate-50 border-none rounded-xl text-sm font-bold focus:ring-2 focus:ring-emerald-500 text-slate-800" placeholder="Contoh: 50000">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Bukti Transfer</label>
                                <input type="file" name="bukti_transfer" accept="image/*" required class="w-full bg-slate-50 border-none rounded-xl text-xs font-medium file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-600 text-slate-800">
                            </div>
                            <div class="pt-4 flex gap-2">
                                <button type="button" @click="showModalSetor = false" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl font-black text-[9px] uppercase tracking-widest">Batal</button>
                                <button type="submit" class="flex-[2] py-3 bg-emerald-500 text-white rounded-xl font-black text-[9px] uppercase tracking-widest shadow-md">Kirim Setoran</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- MODAL TARIK DANA --}}
            <div x-show="showModalTarik" style="display: none;" class="fixed inset-0 z-[150] overflow-y-auto" x-cloak>
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showModalTarik = false"></div>
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div x-show="showModalTarik" class="relative bg-white dark:bg-slate-900 shadow-2xl rounded-[2.5rem] w-full max-w-sm p-6 text-left">
                        <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight mb-1">Tarik <span class="text-rose-500">Dana</span></h3>
                        <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mb-4">Hanya dari Saldo Sukarela</p>
                        <form action="{{ route('warga.koperasi.tarik') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Nominal (Min Rp 10.000)</label>
                                <input type="number" name="amount" required min="10000" max="{{ $akun->saldo_sukarela ?? 0 }}" class="w-full bg-slate-50 border-none rounded-xl text-sm font-bold focus:ring-2 focus:ring-rose-500 text-slate-800" placeholder="Contoh: 50000">
                                <p class="text-[8px] font-bold text-slate-400 uppercase mt-1">Saldo tersedia: Rp{{ number_format($akun->saldo_sukarela ?? 0, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Rekening Tujuan Pencairan</label>
                                <input type="text" name="bank_tujuan" required class="w-full bg-slate-50 border-none rounded-xl text-sm font-bold focus:ring-2 focus:ring-rose-500 text-slate-800" placeholder="Contoh: BCA 123456 a/n Agus">
                            </div>
                            <div class="pt-4 flex gap-2">
                                <button type="button" @click="showModalTarik = false" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl font-black text-[9px] uppercase tracking-widest">Batal</button>
                                <button type="submit" class="flex-[2] py-3 bg-rose-500 text-white rounded-xl font-black text-[9px] uppercase tracking-widest shadow-md">Ajukan Penarikan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- MODAL AJUKAN KASBON --}}
            <div x-show="showModalKasbon" style="display: none;" class="fixed inset-0 z-[150] overflow-y-auto" x-cloak>
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showModalKasbon = false"></div>
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div x-show="showModalKasbon" class="relative bg-white dark:bg-slate-900 shadow-2xl rounded-[2.5rem] w-full max-w-sm p-6 text-left">
                        <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight mb-4">Ajukan <span class="text-amber-500">Kasbon</span></h3>
                        <form action="{{ route('warga.koperasi.pinjam') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Nominal Kasbon</label>
                                <input type="number" name="amount" required min="50000" class="w-full bg-slate-50 border-none rounded-xl text-sm font-bold focus:ring-2 focus:ring-amber-500 text-slate-800" placeholder="Contoh: 1000000">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Tenor Cicilan (Bulan)</label>
                                <select name="tenor" required class="w-full bg-slate-50 border-none rounded-xl text-sm font-bold focus:ring-2 focus:ring-amber-500 text-slate-800">
                                    @for($i=1; $i<=12; $i++)
                                        <option value="{{ $i }}">{{ $i }} Bulan</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Alasan Peminjaman</label>
                                <input type="text" name="alasan" required class="w-full bg-slate-50 border-none rounded-xl text-sm font-bold focus:ring-2 focus:ring-amber-500 text-slate-800" placeholder="Contoh: Kebutuhan medis mendesak">
                            </div>
                            <div class="pt-4 flex gap-2">
                                <button type="button" @click="showModalKasbon = false" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl font-black text-[9px] uppercase tracking-widest">Batal</button>
                                <button type="submit" class="flex-[2] py-3 bg-amber-500 text-white rounded-xl font-black text-[9px] uppercase tracking-widest shadow-md">Ajukan Sekarang</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- MODAL BAYAR CICILAN --}}
            <div x-show="showModalCicilan" style="display: none;" class="fixed inset-0 z-[150] overflow-y-auto" x-cloak>
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showModalCicilan = false"></div>
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div x-show="showModalCicilan" class="relative bg-white dark:bg-slate-900 shadow-2xl rounded-[2.5rem] w-full max-w-sm p-6 text-left">
                        <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight mb-4">Bayar <span class="text-emerald-500">Cicilan</span></h3>
                        <form :action="`/warga/koperasi/cicilan/${cicilanId}`" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 text-center">
                                <p class="text-[9px] font-black text-emerald-600 uppercase tracking-widest mb-1">Nominal Pembayaran</p>
                                <h4 class="text-xl font-black text-emerald-700">Rp<span x-text="nominalCicilan.toLocaleString('id-ID')"></span></h4>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Upload Bukti Transfer</label>
                                <input type="file" name="bukti_transfer" accept="image/*" required class="w-full bg-slate-50 border-none rounded-xl text-xs font-medium file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-600 text-slate-800">
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-2">*Transfer ke rekening koperasi tertera di atas</p>
                            </div>
                            <div class="pt-4 flex gap-2">
                                <button type="button" @click="showModalCicilan = false" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl font-black text-[9px] uppercase tracking-widest">Batal</button>
                                <button type="submit" class="flex-[2] py-3 bg-emerald-500 text-white rounded-xl font-black text-[9px] uppercase tracking-widest shadow-md">Kirim Bukti Pembayaran</button>
                            </div>
                        </form>
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