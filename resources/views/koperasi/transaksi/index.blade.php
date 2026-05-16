<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="text-xl font-black text-slate-800 dark:text-white tracking-tight">
                Data <span class="text-amber-600">Transaksi</span>
            </h2>
            <p class="text-[11px] text-slate-500 font-medium uppercase tracking-wider italic mt-1">Riwayat Setoran dan Penarikan Koperasi</p>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 max-w-7xl mx-auto space-y-6">
        
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-6 sm:p-8 shadow-sm border border-slate-100 dark:border-slate-800">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest">Semua Transaksi</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead>
                        <tr class="text-[10px] font-black uppercase text-slate-400 tracking-widest border-b border-slate-100 dark:border-slate-800">
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Warga</th>
                            <th class="px-4 py-3 text-center">Jenis & Kategori</th>
                            <th class="px-4 py-3 text-right">Nominal</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
                        @forelse($transactions as $trx)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-4 py-4">
                                    <p class="text-xs font-bold text-slate-800 dark:text-slate-200">{{ $trx->created_at->format('d M Y') }}</p>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $trx->created_at->format('H:i') }} WIB</p>
                                </td>
                                <td class="px-4 py-4">
                                    <p class="text-xs font-black text-slate-800 dark:text-white">{{ $trx->user->name }}</p>
                                    <p class="text-[9px] font-bold text-slate-400 italic uppercase">Rumah: {{ $trx->user->no_rumah }}</p>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="text-[9px] font-black uppercase tracking-widest px-2 py-1 rounded-md border {{ $trx->type == 'setoran' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-rose-50 text-rose-600 border-rose-200' }}">
                                        {{ $trx->type }} {{ $trx->kategori }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-right text-xs font-black text-slate-800 dark:text-white">
                                    Rp{{ number_format($trx->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-center">
                                    @if($trx->status == 'pending')
                                        <span class="text-[9px] font-black text-amber-500 uppercase tracking-widest bg-amber-100 px-2 py-0.5 rounded-md">Pending</span>
                                    @elseif($trx->status == 'approved')
                                        <span class="text-[9px] font-black text-emerald-500 uppercase tracking-widest bg-emerald-100 px-2 py-0.5 rounded-md">Sukses</span>
                                    @else
                                        <span class="text-[9px] font-black text-rose-500 uppercase tracking-widest bg-rose-100 px-2 py-0.5 rounded-md">Ditolak</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        @if($trx->bukti_transfer)
                                            <button type="button" onclick="openBuktiModal('{{ asset('storage/' . $trx->bukti_transfer) }}')" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg transition-colors" title="Intip Bukti">
                                                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </button>
                                        @endif

                                        @if($trx->status == 'pending')
                                            <form action="{{ route('koperasi.admin.reject', $trx->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menolak transaksi ini? Warga harus mengajukan ulang.');">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-rose-500 hover:bg-rose-600 text-white rounded-lg text-[9px] font-black uppercase tracking-widest shadow-sm transition-all active:scale-95">
                                                    Tolak
                                                </button>
                                            </form>

                                            <form action="{{ route('koperasi.admin.verify', $trx->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-[9px] font-black uppercase tracking-widest shadow-sm transition-all active:scale-95">
                                                    Terima
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-[10px] font-bold text-slate-400 uppercase italic tracking-widest">
                                    Belum ada data transaksi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-6">
                {{ $transactions->links() }}
            </div>

        </div>
    </div>

    <div id="buktiModal" class="fixed inset-0 z-[99] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-slate-900/75 backdrop-blur-sm" aria-hidden="true" onclick="closeBuktiModal()"></div>

            <div class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-slate-900 shadow-2xl rounded-[2rem] sm:my-8 border border-slate-100 dark:border-slate-800">
                <div class="flex items-center justify-between mb-4 border-b border-slate-50 dark:border-slate-800 pb-4">
                    <h3 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest" id="modal-title">Bukti Transfer Warga</h3>
                    <button type="button" onclick="closeBuktiModal()" class="text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl p-1 transition-all">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                
                <div class="mt-2 flex justify-center bg-slate-50 dark:bg-slate-800/50 rounded-xl p-2">
                    <img id="buktiImageSrc" src="" alt="Memuat Bukti Transfer..." class="max-w-full max-h-[60vh] object-contain rounded-lg shadow-sm">
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="button" onclick="closeBuktiModal()" class="w-full sm:w-auto inline-flex justify-center rounded-xl bg-slate-100 dark:bg-slate-800 px-6 py-2.5 text-[10px] font-black uppercase tracking-widest text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all active:scale-95">
                        Tutup Jendela
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openBuktiModal(imageUrl) {
            document.getElementById('buktiImageSrc').src = imageUrl;
            document.getElementById('buktiModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Kunci scroll layar belakang
        }

        function closeBuktiModal() {
            document.getElementById('buktiModal').classList.add('hidden');
            document.body.style.overflow = 'auto'; // Buka scroll layar belakang
            setTimeout(() => {
                document.getElementById('buktiImageSrc').src = ''; // Bersihkan cache gambar
            }, 300);
        }
    </script>
</x-app-layout>