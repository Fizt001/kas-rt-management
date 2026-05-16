<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="text-lg font-black text-slate-800 dark:text-white tracking-tight leading-tight">
                Penggunaan Dana <span class="text-emerald-600">Mesjid</span>
            </h2>
            <p class="text-[11px] text-slate-500 font-medium uppercase tracking-wider italic">Pencatatan & Bukti Transaksi Keluar</p>
        </div>
    </x-slot>

    <div x-data="{ showModalAdd: false }" class="p-4 sm:p-5 max-w-6xl mx-auto space-y-4">

        @if(session('success'))
        <div class="bg-emerald-500 text-white px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-3 shadow-lg shadow-emerald-500/20">
            <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
        @endif

        <!-- HEADER SUMMARY & TOMBOL -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-900 p-4 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-emerald-50 dark:bg-emerald-900/20 text-emerald-500 flex items-center justify-center shrink-0">
                    <svg class="size-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Pengeluaran Kas</p>
                    <p class="text-xl font-black text-slate-800 dark:text-white tracking-tight">Rp{{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                </div>
            </div>

            <button @click="showModalAdd = true" class="w-full sm:w-auto px-5 py-2.5 bg-emerald-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-emerald-700 transition-all shadow-md active:scale-95 flex items-center justify-center gap-2">
                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Catat Pengeluaran
            </button>
        </div>

        <!-- TABEL PENGELUARAN -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 text-[10px] font-black uppercase tracking-widest text-slate-500 border-b border-slate-200 dark:border-slate-800">
                            <th class="px-5 py-4">Tanggal</th>
                            <th class="px-5 py-4">Rincian Penggunaan</th>
                            <th class="px-5 py-4">Kategori</th>
                            <th class="px-5 py-4 text-right">Nominal</th>
                            <th class="px-5 py-4 text-center">Nota</th>
                            <th class="px-5 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($pengeluaran as $item)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-5 py-4 text-xs font-bold text-slate-600 dark:text-slate-300">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $item->judul }}</p>
                                    @if($item->deskripsi)
                                        <p class="text-[10px] font-medium text-slate-500 truncate max-w-xs mt-0.5">{{ $item->deskripsi }}</p>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <span class="bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-widest border border-blue-100 dark:border-blue-800">
                                        {{ $item->kategori }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right text-sm font-black text-rose-600 dark:text-rose-400">
                                    Rp{{ number_format($item->nominal, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-4 text-center">
                                    @if($item->bukti_nota)
                                        <a href="{{ asset('storage/' . $item->bukti_nota) }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] font-black text-emerald-600 hover:text-emerald-700 uppercase tracking-widest bg-emerald-50 px-2 py-1 rounded-md">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.182 15.182a4.5 4.5 0 01-6.364 0M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z"/></svg>
                                            Lihat
                                        </a>
                                    @else
                                        <span class="text-[10px] text-slate-400 italic font-medium">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <form action="{{ route('mesjid.pengeluaran.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus catatan pengeluaran ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-rose-500 hover:text-rose-700 bg-rose-50 dark:bg-rose-900/20 p-2 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center">
                                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest italic">Belum ada catatan pengeluaran dana mesjid.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MODAL TAMBAH PENGELUARAN -->
        <div x-show="showModalAdd" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showModalAdd = false"></div>
            <div class="flex items-center justify-center min-h-screen p-4">
                <div x-show="showModalAdd" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-md p-6 z-10 shadow-2xl">
                    
                    <h3 class="text-lg font-black text-slate-800 dark:text-white tracking-tight mb-4">Catat Pengeluaran</h3>
                    
                    <form action="{{ route('mesjid.pengeluaran.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1 mb-1 block">Keperluan / Judul</label>
                            <input type="text" name="judul" required placeholder="Contoh: Beli Karpet Mesjid" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl py-2.5 px-3 text-sm font-bold focus:ring-2 focus:ring-emerald-500 dark:text-white">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1 mb-1 block">Nominal (Rp)</label>
                                <input type="number" name="nominal" required min="100" placeholder="500000" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl py-2.5 px-3 text-sm font-bold focus:ring-2 focus:ring-emerald-500 dark:text-white">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1 mb-1 block">Tanggal</label>
                                <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl py-2.5 px-3 text-sm font-bold focus:ring-2 focus:ring-emerald-500 dark:text-white">
                            </div>
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1 mb-1 block">Kategori</label>
                            <select name="kategori" required class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl py-2.5 px-3 text-sm font-bold focus:ring-2 focus:ring-emerald-500 dark:text-white">
                                <option value="Operasional">Operasional (Listrik, Air, Kebersihan)</option>
                                <option value="Pembangunan">Pembangunan / Renovasi</option>
                                <option value="Sosial">Kegiatan Sosial / Santunan</option>
                                <option value="Dakwah">Dakwah / Honor Penceramah</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1 mb-1 block">Keterangan / Deskripsi (Opsional)</label>
                            <textarea name="deskripsi" rows="2" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl py-2 px-3 text-sm font-medium focus:ring-2 focus:ring-emerald-500 dark:text-white" placeholder="Rincian lebih lanjut..."></textarea>
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1 mb-1 block">Foto Nota / Kuitansi (Opsional)</label>
                            <input type="file" name="bukti_nota" accept="image/*" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl py-1.5 px-3 text-[10px] font-bold file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-[9px] file:uppercase file:font-black file:bg-emerald-500 file:text-white hover:file:bg-emerald-600">
                        </div>

                        <div class="pt-3 flex gap-2">
                            <button type="button" @click="showModalAdd = false" class="w-1/3 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200">Batal</button>
                            <button type="submit" class="w-2/3 py-2.5 bg-emerald-600 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-emerald-700 active:scale-95 transition-all">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>