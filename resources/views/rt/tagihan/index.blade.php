<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col -mb-1">
            <h2 class="text-lg font-black text-slate-800 dark:text-white tracking-tight leading-tight">
                Status Tagihan <span class="text-blue-600">Warga</span>
            </h2>
            <p class="text-[10px] text-slate-500 font-medium uppercase tracking-wider italic mt-0.5">Monitoring Pembayaran Iuran Warga Terpusat</p>
        </div>
    </x-slot>

    <div class="px-3 sm:px-5 -mt-5 pb-4 max-w-7xl mx-auto space-y-2.5">
        
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-3 shadow-sm border border-slate-100 dark:border-slate-800 flex flex-col md:flex-row justify-between items-center gap-2">
            <div class="flex items-center gap-2 pl-1 w-full md:w-auto justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-1.5 h-5 bg-blue-600 rounded-full"></div>
                    <h3 class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-widest">Daftar Pembayaran</h3>
                </div>
            </div>

            <form action="{{ route('tagihan.warga') }}" method="GET" class="flex gap-2 w-full md:w-auto">
                <select name="bulan" onchange="this.form.submit()" class="flex-1 md:flex-none bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-1 px-3 text-[10px] font-black focus:ring-2 focus:ring-blue-600 text-slate-700 dark:text-slate-200 uppercase tracking-widest cursor-pointer">
                    @foreach([1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'] as $num => $nama)
                        <option value="{{ $num }}" {{ $bulanIni == $num ? 'selected' : '' }}>{{ substr($nama, 0, 3) }}</option>
                    @endforeach
                </select>
                <select name="tahun" onchange="this.form.submit()" class="flex-1 md:flex-none bg-slate-50 dark:bg-slate-800 border-none rounded-lg py-1 px-3 text-[10px] font-black focus:ring-2 focus:ring-blue-600 text-slate-700 dark:text-slate-200 uppercase tracking-widest cursor-pointer">
                    @for($i = date('Y')-1; $i <= date('Y')+1; $i++)
                        <option value="{{ $i }}" {{ $tahunIni == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </form>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse min-w-max">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 text-[9px] font-black uppercase tracking-[0.2em] text-slate-500 border-b border-slate-100 dark:border-slate-800">
                            <th class="px-4 py-2 whitespace-nowrap">Warga / No. Rumah</th>
                            <th class="px-4 py-2 text-center whitespace-nowrap">Status {{ [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'Mei',6=>'Jun',7=>'Jul',8=>'Ags',9=>'Sep',10=>'Okt',11=>'Nov',12=>'Des'][$bulanIni] ?? 'Bulan Ini' }}</th>
                            <th class="px-4 py-2 text-right whitespace-nowrap">Total Tunggakan</th>
                            <th class="px-4 py-2 text-center whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                        @forelse($rekap as $item)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-4 py-1.5 whitespace-nowrap">
                                <p class="text-xs font-black text-slate-800 dark:text-slate-200 leading-tight">{{ $item['nama'] }}</p>
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Blok: {{ $item['no_rumah'] }}</p>
                            </td>
                            <td class="px-4 py-1.5 text-center whitespace-nowrap">
                                @if($item['status_sekarang'] == 'lunas')
                                    <span class="bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-md text-[8px] font-black uppercase border border-emerald-100">Lunas</span>
                                @elseif($item['status_sekarang'] == 'pending')
                                    <span class="bg-amber-50 text-amber-600 px-2 py-0.5 rounded-md text-[8px] font-black uppercase border border-amber-100">Pending</span>
                                @elseif($item['status_sekarang'] == 'belum_ada')
                                    <span class="bg-slate-50 text-slate-500 px-2 py-0.5 rounded-md text-[8px] font-black uppercase border border-slate-200">Tidak Ada Tagihan</span>
                                @else
                                    <span class="bg-rose-50 text-rose-600 px-2 py-0.5 rounded-md text-[8px] font-black uppercase border border-rose-100">Belum Bayar</span>
                                @endif
                            </td>
                            <td class="px-4 py-1.5 text-right whitespace-nowrap">
                                <span class="text-xs font-black {{ $item['total_tunggakan'] > 0 ? 'text-rose-600' : 'text-slate-400' }}">
                                    Rp {{ number_format($item['total_tunggakan'], 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-4 py-1.5 text-center whitespace-nowrap">
                                @if($item['total_tunggakan'] > 0)
                                    <a href="https://wa.me/{{ $item['wa_phone'] }}?text=Halo%20{{ $item['nama'] }},%20mengingatkan%20pembayaran%20kas%20RT%20sebesar%20Rp{{ number_format($item['total_tunggakan'], 0, ',', '.') }}.%20Terima%20kasih." 
                                       target="_blank" 
                                       class="inline-flex items-center gap-1 bg-emerald-500 hover:bg-emerald-600 text-white px-2 py-1 rounded-lg text-[8px] font-black uppercase transition-all active:scale-95 shadow-sm shadow-emerald-200 dark:shadow-none">
                                        <svg class="size-2.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                        Nagih
                                    </a>
                                @else
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest italic">Berees!</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-4 text-center">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">Belum ada data warga.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="p-3 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 flex flex-col sm:flex-row justify-between items-center gap-3">
                
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest hidden sm:block">
                    Menampilkan data {{ $rekap->firstItem() ?? 0 }} - {{ $rekap->lastItem() ?? 0 }} dari {{ $rekap->total() }} Warga
                </p>

                <div class="flex gap-2 w-full sm:w-auto justify-between sm:justify-end">
                    @if ($rekap->onFirstPage())
                        <span class="px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-800 text-[9px] font-black uppercase cursor-not-allowed text-center flex-1 sm:flex-none">
                            Sebelumnya
                        </span>
                    @else
                        <a href="{{ $rekap->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-[9px] font-black uppercase transition-colors shadow-sm text-center flex-1 sm:flex-none">
                            Sebelumnya
                        </a>
                    @endif

                    @if ($rekap->hasMorePages())
                        <a href="{{ $rekap->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-blue-200 text-blue-600 bg-blue-50 hover:bg-blue-100 text-[9px] font-black uppercase transition-colors shadow-sm text-center flex-1 sm:flex-none">
                            Selanjutnya
                        </a>
                    @else
                        <span class="px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-800 text-[9px] font-black uppercase cursor-not-allowed text-center flex-1 sm:flex-none">
                            Selanjutnya
                        </span>
                    @endif
                </div>
                
            </div>
            
        </div>
    </div>
</x-app-layout>