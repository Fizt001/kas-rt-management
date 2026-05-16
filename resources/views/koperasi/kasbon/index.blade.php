<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="text-xl font-black text-slate-800 dark:text-white tracking-tight">
                Data <span class="text-amber-600">Kasbon Warga</span>
            </h2>
            <p class="text-[11px] text-slate-500 font-medium uppercase tracking-wider italic mt-1">Rekapitulasi Pinjaman dan Dana Darurat</p>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 max-w-7xl mx-auto space-y-6">
        
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-6 sm:p-8 shadow-sm border border-slate-100 dark:border-slate-800">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest">Semua Riwayat Pinjaman</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead>
                        <tr class="text-[10px] font-black uppercase text-slate-400 tracking-widest border-b border-slate-100 dark:border-slate-800">
                            <th class="px-4 py-3">Tanggal / Warga</th>
                            <th class="px-4 py-3">Alasan Pinjam</th>
                            <th class="px-4 py-3 text-center">Tenor</th>
                            <th class="px-4 py-3 text-right">Nominal</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-right">Progress</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
                        @forelse($loans as $loan)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-4 py-4">
                                    <p class="text-xs font-black text-slate-800 dark:text-white uppercase">{{ $loan->user->name }}</p>
                                    <p class="text-[9px] font-bold text-slate-400 italic uppercase">{{ $loan->created_at->format('d M Y') }}</p>
                                </td>
                                <td class="px-4 py-4">
                                    <p class="text-[10px] font-bold text-slate-600 dark:text-slate-400 max-w-[200px] truncate" title="{{ $loan->alasan }}">
                                        {{ $loan->alasan }}
                                    </p>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="text-[9px] font-black uppercase tracking-widest px-2 py-1 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                                        {{ $loan->tenor }} Bln
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-right text-xs font-black text-slate-800 dark:text-white">
                                    Rp{{ number_format($loan->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-center">
                                    @if($loan->status == 'pending')
                                        <span class="text-[9px] font-black text-amber-500 uppercase tracking-widest bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-md">Pending</span>
                                    @elseif($loan->status == 'approved')
                                        <span class="text-[9px] font-black text-blue-500 uppercase tracking-widest bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-md">Aktif</span>
                                    @elseif($loan->status == 'lunas')
                                        <span class="text-[9px] font-black text-emerald-500 uppercase tracking-widest bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md">Lunas</span>
                                    @else
                                        <span class="text-[9px] font-black text-rose-500 uppercase tracking-widest bg-rose-50 border border-rose-200 px-2 py-0.5 rounded-md">Ditolak</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-right">
                                    @if($loan->status == 'approved' || $loan->status == 'lunas')
                                        @php
                                            $lunasCount = $loan->installments->where('status', 'lunas')->count();
                                            $percent = ($loan->tenor > 0) ? ($lunasCount / $loan->tenor) * 100 : 0;
                                        @endphp
                                        <div class="flex flex-col items-end gap-1">
                                            <span class="text-[9px] font-black text-slate-500 tracking-widest">{{ $lunasCount }} / {{ $loan->tenor }} Lunas</span>
                                            <div class="w-20 h-1.5 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                                                <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $percent }}%"></div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-[10px] font-bold text-slate-400 italic">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-[10px] font-bold text-slate-400 uppercase italic tracking-widest">
                                    Belum ada data pinjaman kasbon.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-6">
                {{ $loans->links() }}
            </div>

        </div>
    </div>
</x-app-layout>