<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="text-xl font-black text-slate-800 dark:text-white tracking-tight">
                Data <span class="text-amber-600">Anggota Koperasi</span>
            </h2>
            <p class="text-[11px] text-slate-500 font-medium uppercase tracking-wider italic mt-1">Rincian Saldo Tabungan per Warga</p>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 max-w-7xl mx-auto space-y-6">
        
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-6 sm:p-8 shadow-sm border border-slate-100 dark:border-slate-800">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest">Daftar Buku Tabungan</h3>
                <span class="bg-amber-100 text-amber-600 text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest">
                    {{ $accounts->total() }} Anggota
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead>
                        <tr class="text-[10px] font-black uppercase text-slate-400 tracking-widest border-b border-slate-100 dark:border-slate-800">
                            <th class="px-4 py-3">Informasi Warga</th>
                            <th class="px-4 py-3 text-right">Saldo Pokok</th>
                            <th class="px-4 py-3 text-right">Saldo Wajib</th>
                            <th class="px-4 py-3 text-right">Saldo Sukarela</th>
                            <th class="px-4 py-3 text-right">Total Tabungan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
                        @forelse($accounts as $acc)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="size-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center font-black text-sm border border-amber-100">
                                            {{ substr($acc->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-xs font-black text-slate-800 dark:text-white uppercase">{{ $acc->user->name }}</p>
                                            <p class="text-[9px] font-bold text-slate-400 italic uppercase">Rumah: {{ $acc->user->no_rumah ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-right text-xs font-bold text-slate-500">
                                    Rp{{ number_format($acc->saldo_pokok, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-right text-xs font-bold text-slate-500">
                                    Rp{{ number_format($acc->saldo_wajib, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-right text-xs font-bold text-slate-500">
                                    Rp{{ number_format($acc->saldo_sukarela, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <span class="text-xs font-black text-amber-600 bg-amber-50 dark:bg-amber-900/10 px-3 py-1.5 rounded-lg border border-amber-100 dark:border-amber-800/30">
                                        Rp{{ number_format($acc->total_saldo, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-12 text-center text-[10px] font-bold text-slate-400 uppercase italic tracking-widest">
                                    Belum ada data anggota koperasi terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-6">
                {{ $accounts->links() }}
            </div>

        </div>
    </div>
</x-app-layout>