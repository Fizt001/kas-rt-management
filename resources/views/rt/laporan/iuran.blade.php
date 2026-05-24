<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="text-xl font-black text-slate-800 dark:text-white tracking-tight leading-tight">
                Laporan <span class="text-amber-500">Iuran Warga</span>
            </h2>
            <p class="text-[11px] text-slate-500 font-medium uppercase tracking-wider italic">Rekap Pembayaran & Tunggakan Kas RT</p>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8 max-w-[90rem] mx-auto space-y-4 md:space-y-5">
        
        <!-- FILTER TAHUN -->
        <div class="flex justify-between items-center bg-white dark:bg-slate-900 p-4 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
            <div class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest">
                Rekap Tahun {{ $tahun }}
            </div>
            <form action="{{ route('laporan.iuran') }}" method="GET" class="flex gap-2">
                <select name="tahun" onchange="this.form.submit()" class="bg-slate-50 dark:bg-slate-800 border-none rounded-xl py-1.5 px-3 text-[10px] font-black focus:ring-2 focus:ring-amber-500 cursor-pointer text-slate-700 dark:text-slate-200">
                    @for($i = date('Y') - 1; $i <= date('Y') + 1; $i++)
                        <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </form>
        </div>

        <!-- TABEL RINCIAN WARGA (COMPACT) -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-[9px] font-black uppercase tracking-widest text-slate-400">
                            <th class="px-5 py-3">Nama Warga</th>
                            <th class="px-5 py-3 text-center">Tagihan Dibuat</th>
                            <th class="px-5 py-3 text-right">Sudah Dibayar (Lunas)</th>
                            <th class="px-5 py-3 text-right">Sisa Tunggakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach($wargaList as $warga)
                            @php
                                $totalTagihanBulanIni = $warga->billings->count();
                                $totalLunas = $warga->billings->where('status', 'lunas')->sum('total_amount');
                                $totalNunggak = $warga->billings->whereIn('status', ['belum_lunas', 'pending'])->sum('total_amount');
                            @endphp
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-all">
                                <td class="px-5 py-3">
                                    <div class="text-sm font-black text-slate-800 dark:text-slate-200 uppercase">{{ $warga->name }}</div>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <span class="bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 py-0.5 px-2 rounded-md text-[10px] font-bold">
                                        {{ $totalTagihanBulanIni }} Bulan
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <span class="text-sm font-black text-emerald-600 dark:text-emerald-400">
                                        Rp {{ number_format($totalLunas, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    @if($totalNunggak > 0)
                                        <span class="text-sm font-black text-rose-600 dark:text-rose-500 italic bg-rose-50 dark:bg-rose-900/20 px-2 py-0.5 rounded-md">
                                            Rp {{ number_format($totalNunggak, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">- Bebas Tunggakan -</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>