<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="text-xl font-black text-slate-800 dark:text-white tracking-tight leading-tight">
                Tagihan Iuran 
            </h2>
            <p class="text-[11px] text-slate-500 font-medium uppercase tracking-wider italic">Riwayat pembayaran KAS & Sampah</p>
        </div>
    </x-slot>

    <div x-data="{ 
        showModalBayar: false, 
        showQrisModal: false,
        bayarUrl: '',
        tagihanBulan: '',
        tagihanNominal: '',
        
        openBayarModal(id, bulan, tahun, nominal) {
            this.bayarUrl = `/my-iuran/bayar/${id}`;
            this.tagihanBulan = `${bulan} ${tahun}`;
            this.tagihanNominal = 'Rp ' + new Intl.NumberFormat('id-ID').format(nominal);
            this.showModalBayar = true;
        }
    }" class="p-4 sm:p-5 max-w-7xl mx-auto space-y-4 md:space-y-5">

        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
            @forelse($masters as $index => $m)
                @php
                    $gradients = [
                        'from-blue-500 to-indigo-600 shadow-blue-500/30',
                        'from-emerald-400 to-teal-500 shadow-emerald-500/30',
                        'from-amber-400 to-orange-500 shadow-amber-500/30',
                        'from-rose-400 to-pink-500 shadow-rose-500/30'
                    ];
                    $bgClass = $gradients[$index % 4];
                @endphp
                <div class="bg-gradient-to-br {{ $bgClass }} rounded-2xl p-4 shadow-lg text-white relative overflow-hidden group">
                    <svg class="absolute -right-4 -bottom-4 opacity-20 w-16 h-16 group-hover:scale-110 transition-transform duration-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.64-2.25 1.64-1.74 0-2.26-.95-2.32-1.81H7.84c.09 1.71 1.25 2.85 3.06 3.2V19h2.33v-1.66c1.64-.32 2.92-1.38 2.92-3.03 0-2.19-1.76-2.8-3.84-3.17z"/></svg>
                    <p class="text-[9px] font-black text-white/80 uppercase tracking-widest relative z-10">{{ $m->nama_iuran }}</p>
                    <p class="text-base sm:text-lg font-black text-white mt-0.5 italic relative z-10">Rp{{ number_format($m->nominal, 0, ',', '.') }}</p>
                </div>
            @empty
                <div class="bg-slate-100 dark:bg-slate-800 rounded-2xl p-4 col-span-full text-center border border-dashed border-slate-300 dark:border-slate-700">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">Belum ada iuran master yang aktif</p>
                </div>
            @endforelse
        </div>

        <div class="flex flex-col lg:flex-row gap-4 md:gap-5">
            
            <div class="flex-1 bg-white dark:bg-slate-900 rounded-3xl p-5 sm:p-6 shadow-sm border border-slate-100 dark:border-slate-800">
                
                <div class="flex flex-col xl:flex-row gap-3 justify-between items-start xl:items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex flex-col">
                        <h3 class="text-sm font-black text-slate-800 dark:text-white tracking-widest italic uppercase">Payment Status {{ $tahun }}</h3>
                        @if($isAdmin && $targetUser->id != auth()->id())
                            <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mt-1">
                                Memantau: {{ $targetUser->name }}
                            </p>
                        @endif
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="flex gap-3 text-[9px] font-black tracking-widest uppercase text-slate-500 mr-2">
                            <span class="flex items-center gap-1.5"><div class="w-2 h-2 rounded-full bg-emerald-500"></div> Paid</span>
                            <span class="flex items-center gap-1.5"><div class="w-2 h-2 rounded-full bg-amber-400"></div> Wait</span>
                        </div>
                        
                        <form action="{{ route('warga.iuran') }}" method="GET" class="flex flex-wrap items-center gap-2">
                            @if($isAdmin)
                            <select name="warga_id" onchange="this.form.submit()" class="bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-800 rounded-lg py-1.5 px-3 text-[10px] uppercase tracking-wider font-black focus:ring-2 focus:ring-blue-600 cursor-pointer max-w-[180px]">
                                <option value="{{ auth()->id() }}">Data Saya Sendiri</option>
                                @foreach($daftarWarga as $w)
                                    <option value="{{ $w->id }}" {{ request('warga_id') == $w->id ? 'selected' : '' }}>
                                        {{ $w->name }}
                                    </option>
                                @endforeach
                            </select>
                            @endif

                            <select name="tahun" onchange="this.form.submit()" class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-white border-none rounded-lg py-1.5 px-3 text-[10px] font-black focus:ring-2 focus:ring-blue-600 cursor-pointer">
                                @for($i = date('Y') - 1; $i <= date('Y') + 1; $i++)
                                    <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </form>
                    </div>
                </div>

                <div class="space-y-4">
                    @php
                        $bulanList = [
                            1=>['indo'=>'Januari','eng'=>'JAN'], 2=>['indo'=>'Februari','eng'=>'FEB'], 3=>['indo'=>'Maret','eng'=>'MAR'],
                            4=>['indo'=>'April','eng'=>'APR'], 5=>['indo'=>'Mei','eng'=>'MAY'], 6=>['indo'=>'Juni','eng'=>'JUN'],
                            7=>['indo'=>'Juli','eng'=>'JUL'], 8=>['indo'=>'Agustus','eng'=>'AUG'], 9=>['indo'=>'September','eng'=>'SEP'],
                            10=>['indo'=>'Oktober','eng'=>'OCT'], 11=>['indo'=>'November','eng'=>'NOV'], 12=>['indo'=>'Desember','eng'=>'DEC']
                        ];
                    @endphp

                    @foreach($bulanList as $num => $bln)
                        @php
                            $tagihanBulanIni = $tagihan->first(function($item) use ($bln, $num) {
                                return strtolower($item->bulan) == strtolower($bln['indo']) || $item->bulan == $num || $item->bulan == str_pad($num, 2, '0', STR_PAD_LEFT);
                            });
                            
                            $isGenerated = !is_null($tagihanBulanIni);
                            $totalAmount = $isGenerated ? $tagihanBulanIni->total_amount : 0;
                            $status = $isGenerated ? $tagihanBulanIni->status : 'kosong';
                            $firstId = $isGenerated ? $tagihanBulanIni->id : null;
                        @endphp
                        
                        <div class="flex items-center gap-3 group">
                            <div class="w-8 sm:w-10 text-[10px] font-black {{ $isGenerated ? 'text-slate-800 dark:text-slate-200' : 'text-slate-300 dark:text-slate-600' }} uppercase tracking-widest">{{ $bln['eng'] }}</div>
                            
                            <div class="flex-1 h-[4px] rounded-full overflow-hidden {{ $isGenerated ? 'bg-slate-100 dark:bg-slate-800' : 'bg-slate-50 dark:bg-slate-800/30 border-b border-dotted border-slate-200 dark:border-slate-700' }}">
                                @if($status == 'lunas')
                                    <div class="h-full bg-gradient-to-r from-emerald-400 to-emerald-500 w-full shadow-[0_0_10px_rgba(16,185,129,0.4)]"></div>
                                @elseif($status == 'pending')
                                    <div class="h-full bg-gradient-to-r from-amber-300 to-amber-400 w-full shadow-[0_0_10px_rgba(251,191,36,0.4)]"></div>
                                @elseif($status == 'belum_lunas')
                                    <div class="h-full bg-slate-300 dark:bg-slate-600 w-full transition-all group-hover:bg-blue-300"></div>
                                @endif
                            </div>
                            
                            <div class="w-[120px] sm:w-[150px] flex items-center justify-end gap-2 sm:gap-4">
                                @if($isGenerated)
                                    <div class="text-[10px] sm:text-xs font-black text-slate-800 dark:text-slate-200 italic truncate">
                                        Rp{{ number_format($totalAmount, 0, ',', '.') }}
                                    </div>
                                    
                                    <div class="w-16 flex justify-end">
                                        @if($status == 'lunas')
                                            <div class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-500/30">
                                                <svg class="size-3" fill="none" stroke="currentColor" stroke-width="4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                            </div>
                                        @elseif($status == 'pending')
                                            <span class="text-[8px] font-black text-amber-500 uppercase tracking-widest bg-amber-50 px-2 py-1 rounded-md">Wait</span>
                                        @else
                                            @if($targetUser->id == auth()->id())
                                                <button type="button" @click="openBayarModal('{{ $firstId }}', '{{ $bln['indo'] }}', '{{ $tahun }}', '{{ $totalAmount }}')" 
                                                    class="px-3 py-1 bg-blue-600 text-white rounded-md text-[9px] font-black uppercase tracking-widest hover:bg-blue-700 transition-all shadow-md shadow-blue-500/30 active:scale-95">
                                                    Bayar
                                                </button>
                                            @else
                                                <span class="text-[8px] font-black text-rose-500 uppercase tracking-widest bg-rose-50 px-2 py-1 rounded-md">Belum Lunas</span>
                                            @endif
                                        @endif
                                    </div>
                                @else
                                    <div class="w-full text-right">
                                        <span class="text-[8px] sm:text-[9px] font-bold text-slate-300 dark:text-slate-600 uppercase tracking-widest italic">Belum Ada</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="w-full lg:w-[300px] space-y-4">
                <div class="bg-white dark:bg-slate-900 rounded-3xl p-5 sm:p-6 shadow-sm border border-slate-100 dark:border-slate-800 h-full">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Metode Pembayaran</h3>
                    
                    @if($setting)
                        <div class="space-y-3">
                            <div class="bg-gradient-to-br from-slate-800 to-slate-900 dark:from-slate-800 dark:to-slate-950 p-4 rounded-2xl border border-slate-700 shadow-xl shadow-slate-900/10 text-white relative overflow-hidden">
                                <div class="absolute -right-4 -top-4 opacity-10">
                                    <svg class="size-24" fill="currentColor" viewBox="0 0 24 24"><path d="M4 10h16v2H4zm0 4h16v2H4zm0 4h16v2H4zm0-12h16v2H4zM2 4h20v16H2V4z"/></svg>
                                </div>
                                <div class="relative z-10">
                                    <div class="text-blue-400 font-black text-lg italic tracking-tighter mb-2">{{ $setting->nama_bank ?? 'BANK' }}</div>
                                    <p class="text-sm font-black tracking-widest">{{ $setting->nomor_rekening ?? '-' }}</p>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">A/N {{ $setting->nama_penerima ?? 'PENGURUS RT' }}</p>
                                </div>
                            </div>

                            @if(isset($setting->qris_path) && $setting->qris_path)
                                <button type="button" @click="showQrisModal = true" class="w-full flex justify-between items-center bg-blue-50 dark:bg-blue-900/20 p-4 rounded-2xl border border-blue-100 dark:border-blue-800/50 hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-all group">
                                    <div class="flex items-center gap-3 text-blue-600 dark:text-blue-400">
                                        <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5zM15 15h.008v.008H15V15zM18.75 15h.008v.008h-.008V15zM15 18.75h.008v.008H15v-.008zM18.75 18.75h.008v.008h-.008v-.008zM13.5 13.5h.008v.008h-.008v-.008zM13.5 18.75h.008v.008h-.008v-.008zM18.75 13.5h.008v.008h-.008v-.008z"/></svg>
                                        <span class="text-[10px] font-black uppercase tracking-widest">Bayar via QRIS</span>
                                    </div>
                                    <svg class="size-4 text-blue-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                                </button>
                            @endif
                        </div>
                    @else
                        <div class="text-center p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-dashed border-slate-200 dark:border-slate-700">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Info Pembayaran Belum Diatur RT</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if($setting && isset($setting->qris_path) && $setting->qris_path)
        <div x-show="showQrisModal" @keydown.window.escape="showQrisModal = false" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" @click="showQrisModal = false"></div>
            <div class="flex items-center justify-center min-h-screen p-4 text-center z-10 relative">
                <div x-show="showQrisModal" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
                     class="relative bg-white dark:bg-slate-900 rounded-3xl shadow-2xl max-w-sm w-full p-6 border border-slate-200 dark:border-slate-800">
                    
                    <button type="button" @click="showQrisModal = false" class="absolute -top-4 -right-4 bg-rose-500 text-white rounded-full p-2 shadow-lg hover:bg-rose-600 transition-colors">
                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    
                    <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest mb-4">Scan QRIS Kas RT</h3>
                    <div class="bg-slate-100 dark:bg-slate-800 p-4 rounded-2xl flex justify-center items-center">
                        <img src="{{ asset('storage/' . $setting->qris_path) }}" class="w-full max-w-[200px] rounded-xl object-contain" alt="QRIS">
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div x-show="showModalBayar" @keydown.window.escape="showModalBayar = false" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="showModalBayar = false"></div>
            <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0 relative">
                <div x-show="showModalBayar" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative bg-white border border-slate-200 shadow-2xl rounded-3xl w-full max-w-sm mx-auto p-6 text-left transform transition-all dark:bg-slate-900 dark:border-slate-800">
                    
                    <div class="flex justify-between items-center mb-5">
                        <h3 class="text-lg font-black text-slate-800 dark:text-white tracking-tight italic">Pilih <span class="text-blue-600">Metode</span></h3>
                        <button type="button" @click="showModalBayar = false" class="text-slate-400 hover:text-slate-600"><svg class="size-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 bg-slate-50 dark:bg-slate-800/50 p-4 rounded-2xl border border-slate-100 dark:border-slate-700 mb-5">
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Periode</p>
                            <p class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase" x-text="tagihanBulan"></p>
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Total Bayar</p>
                            <p class="text-sm font-black text-blue-600 italic" x-text="tagihanNominal"></p>
                        </div>
                    </div>



                    <form :action="bayarUrl" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Upload Struk / Layar HP</label>
                            <input type="file" name="bukti_transfer" accept="image/*" required 
                                class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl py-3 px-4 text-xs font-bold focus:ring-2 focus:ring-blue-600 transition-all dark:text-white file:mr-4 file:py-1.5 file:px-4 file:rounded-full file:border-0 file:text-[9px] file:uppercase file:tracking-widest file:font-black file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                        </div>

                        <div class="pt-2 flex gap-2">
                            <button type="button" @click="showModalBayar = false" class="w-1/3 py-3 bg-slate-100 text-slate-500 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all">Batal</button>
                            <button type="submit" class="w-2/3 py-3 bg-blue-600 text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-blue-500/30 hover:bg-blue-700 transition-all active:scale-95">Upload Struk</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>