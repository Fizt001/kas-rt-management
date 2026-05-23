<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="text-xl font-black text-slate-800 dark:text-white tracking-tight">Verifikasi <span class="text-blue-600">Pembayaran</span></h2>
            <p class="text-xs text-slate-500 font-medium uppercase tracking-wider italic">Cek dan validasi bukti transfer warga</p>
        </div>
    </x-slot>

    <div class="p-5 space-y-5 pb-10">
        
        <!-- 1. CARD RINCIAN IURAN GLOBAL -->
        <div class="bg-blue-600 dark:bg-slate-800 rounded-xl p-5 shadow-lg shadow-blue-200 dark:shadow-none text-white relative overflow-hidden">
            <svg class="absolute -right-6 -top-6 opacity-10 w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.64-2.25 1.64-1.74 0-2.26-.95-2.32-1.81H7.84c.09 1.71 1.25 2.85 3.06 3.2V19h2.33v-1.66c1.64-.32 2.92-1.38 2.92-3.03 0-2.19-1.76-2.8-3.84-3.17z"/></svg>
            
            <h3 class="text-[10px] font-black uppercase tracking-widest text-blue-200 mb-3">Informasi Nominal Iuran Berlaku</h3>
            <div class="flex flex-wrap gap-4 items-end">
                @forelse($masterIurans as $master)
                <div class="bg-white/10 border border-white/20 rounded-lg p-3 min-w-[120px]">
                    <p class="text-[9px] font-bold text-blue-100 uppercase tracking-wider">{{ $master->nama_iuran }}</p>
                    <p class="text-sm font-black mt-1">Rp {{ number_format($master->nominal, 0, ',', '.') }}</p>
                </div>
                @empty
                <div class="bg-white/10 border border-white/20 rounded-lg p-3">
                    <p class="text-[9px] font-bold text-blue-100 uppercase tracking-wider">Default Iuran</p>
                    <p class="text-sm font-black mt-1">Rp 0</p>
                </div>
                @endforelse
                
                <div class="ml-auto bg-yellow-400 text-slate-900 rounded-lg p-3 min-w-[140px] shadow-sm">
                    <p class="text-[9px] font-black uppercase tracking-widest opacity-80">Total Tagihan Bulanan</p>
                    <p class="text-lg font-black mt-0.5">Rp {{ number_format($masterIurans->sum('nominal'), 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- 2. FILTER SECTION -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-3 shadow-sm flex flex-wrap gap-3 items-center justify-between">
            <form action="{{ route('verifikasi.index') }}" method="GET" class="flex flex-wrap gap-2 w-full md:w-auto">
                <select name="bulan" class="bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-2 px-3 text-xs font-bold focus:ring-2 focus:ring-blue-600 dark:text-white">
                    <option value="">Semua Bulan</option>
                    @foreach([1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'] as $num => $bln)
                        <option value="{{ $num }}" {{ request('bulan') == $num ? 'selected' : '' }}>{{ $bln }}</option>
                    @endforeach
                </select>

                <select name="tahun" class="bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-2 px-3 text-xs font-bold focus:ring-2 focus:ring-blue-600 dark:text-white">
                    <option value="">Semua Tahun</option>
                    <option value="2026" {{ request('tahun') == '2026' ? 'selected' : '' }}>2026</option>
                    <option value="2027" {{ request('tahun') == '2027' ? 'selected' : '' }}>2027</option>
                </select>

                <button type="submit" class="bg-slate-800 text-white px-4 py-2 rounded-lg text-xs font-black uppercase tracking-wider hover:bg-slate-700 transition-colors">
                    Filter
                </button>
            </form>
        </div>

        <!-- 3. TABEL VERIFIKASI -->
        <div x-data="{ imgModal: false, imgSrc: '' }" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-[10px] font-black uppercase tracking-widest text-slate-400">
                        <th class="px-6 py-4">Warga & Periode</th>
                        <th class="px-6 py-4">Total Nominal</th>
                        <th class="px-6 py-4 text-center">Bukti Trf</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($payments as $pay)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-all">
                        <td class="px-6 py-4">
                            <div class="text-sm font-black text-slate-800 dark:text-slate-200 uppercase">{{ $pay->user->name ?? 'Warga' }}</div>
                            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mt-0.5">
                                {{ [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'][$pay->bulan] ?? 'Bulan '.$pay->bulan }} {{ $pay->tahun }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <!-- Nominal dengan Trik Fallback, jadi kebal error walau salah kolom DB -->
                            <span class="text-sm font-black text-emerald-600 dark:text-emerald-400">
                                Rp {{ number_format($pay->total_amount ?? $pay->amount ?? 0, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button @click="imgSrc = '{{ asset('storage/' . $pay->bukti_transfer) }}'; imgModal = true" type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-black uppercase hover:bg-blue-100 transition-colors border border-blue-100">
                                <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                                Lihat
                            </button>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form id="form-approve-{{ $pay->id }}" action="{{ route('verifikasi.approve', $pay->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="button" onclick="confirmApproval('{{ $pay->id }}', '{{ addslashes($pay->user->name ?? 'Warga') }}', '{{ [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'][$pay->bulan] ?? 'Bulan '.$pay->bulan }}', '{{ $pay->tahun }}')" class="py-2 px-4 bg-emerald-500 text-white text-[10px] uppercase tracking-widest font-black rounded-lg hover:bg-emerald-600 shadow-md shadow-emerald-200 transition-all active:scale-95">
                                    Setujui
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.2em]">Tidak Ada Pembayaran Menunggu</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-800 flex justify-center">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    Total: {{ $payments->count() }} Data Menunggu Verifikasi
                </p>
            </div>

            <!-- MODAL ALPINE UNTUK LIHAT BUKTI GAMBAR -->
            <div x-show="imgModal" @keydown.window.escape="imgModal = false" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto">
                <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" @click="imgModal = false"></div>
                <div class="flex items-center justify-center min-h-screen p-4 text-center z-10 relative">
                    <div x-show="imgModal" 
                         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
                         class="relative bg-white rounded-2xl shadow-2xl max-w-xl w-full p-2">
                        
                        <button type="button" @click="imgModal = false" class="absolute -top-4 -right-4 bg-rose-500 text-white rounded-full p-2 shadow-lg hover:bg-rose-600 transition-colors">
                            <svg class="size-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        
                        <img :src="imgSrc" class="w-full rounded-xl object-contain max-h-[80vh]" alt="Bukti Transfer">
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function confirmApproval(id, name, month, year) {
                Swal.fire({
                    title: 'Verifikasi Pembayaran?',
                    html: `Anda akan memvalidasi pembayaran dari <b class="uppercase">${name}</b> untuk periode <b>${month} ${year}</b>.<br><br><span class="text-xs text-slate-500">Pastikan nominal dan bukti transfer sudah sesuai sebelum disetujui.</span>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Ya, Validasi!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form-approve-' + id).submit();
                    }
                })
            }
        </script>
    @endpush
</x-app-layout>