<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="text-xl font-black text-slate-800 dark:text-white tracking-tight">
                Analitik <span class="text-amber-600">Koperasi RT</span>
            </h2>
            <p class="text-[11px] text-slate-500 font-medium uppercase tracking-wider italic mt-1">Ringkasan Keuangan & Pusat Kendali</p>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 max-w-7xl mx-auto space-y-6">
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-slate-900 p-6 rounded-[2.5rem] text-white shadow-xl relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-[9px] font-black uppercase text-emerald-400 tracking-[0.2em] mb-1">Kas Tersedia (Siap Cair)</p>
                    <h3 class="text-2xl font-black text-white tracking-tight">Rp{{ number_format($totalDana ?? 0, 0, ',', '.') }}</h3>
                </div>
                <svg class="absolute -right-4 -bottom-4 size-24 text-white/5 transform group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 14h-2v-2h2v2zm0-4h-2V7h2v5z"/></svg>
            </div>

            <div class="bg-blue-500 p-6 rounded-[2.5rem] text-white shadow-xl relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-[9px] font-black uppercase text-blue-200 tracking-[0.2em] mb-1">Uang Beredar (Utang Warga)</p>
                    <h3 class="text-2xl font-black text-white tracking-tight">Rp{{ number_format($uangBeredar ?? 0, 0, ',', '.') }}</h3>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 p-6 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm">
                <p class="text-[9px] font-black uppercase text-slate-400 tracking-[0.2em] mb-1">Total Aset Koperasi</p>
                <h3 class="text-xl font-black text-slate-800 dark:text-white tracking-tight">Rp{{ number_format(($totalDana ?? 0) + ($uangBeredar ?? 0), 0, ',', '.') }}</h3>
                <p class="text-[9px] font-bold text-slate-400 mt-2">Gabungan Kas + Piutang</p>
            </div>
        </div>

        <div>
            <h3 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest mb-4 ml-2">Tugas Menunggu Verifikasi</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                
                <div class="bg-white dark:bg-slate-900 rounded-3xl p-5 border border-slate-100 dark:border-slate-800 flex items-center justify-between shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-center gap-4">
                        <div class="size-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center">
                            <svg class="size-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Setoran / Tarik</p>
                            <h4 class="text-lg font-black text-slate-800 dark:text-white">{{ $countPendingTransaksi }} <span class="text-xs font-bold text-slate-500">Antrean</span></h4>
                        </div>
                    </div>
                    <a href="{{ route('koperasi.admin.transaksi') }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-amber-500 hover:text-white transition-all">Proses</a>
                </div>

                <div class="bg-white dark:bg-slate-900 rounded-3xl p-5 border border-slate-100 dark:border-slate-800 flex items-center justify-between shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-center gap-4">
                        <div class="size-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center">
                            <svg class="size-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Pengajuan Kasbon</p>
                            <h4 class="text-lg font-black text-slate-800 dark:text-white">{{ $countPendingKasbon }} <span class="text-xs font-bold text-slate-500">Antrean</span></h4>
                        </div>
                    </div>
                    <a href="{{ route('koperasi.admin.kasbon') }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-500 hover:text-white transition-all">Proses</a>
                </div>

                <div class="bg-white dark:bg-slate-900 rounded-3xl p-5 border border-slate-100 dark:border-slate-800 flex items-center justify-between shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-center gap-4">
                        <div class="size-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                            <svg class="size-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Pembayaran Cicilan</p>
                            <h4 class="text-lg font-black text-slate-800 dark:text-white">{{ $countPendingCicilan }} <span class="text-xs font-bold text-slate-500">Antrean</span></h4>
                        </div>
                    </div>
                    <a href="{{ route('koperasi.admin.kasbon') }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-500 hover:text-white transition-all">Proses</a>
                </div>

            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-6">
            
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-7 shadow-sm border border-slate-100 dark:border-slate-800">
                <h3 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest mb-6 border-b border-slate-50 dark:border-slate-800 pb-4">Komposisi Simpanan Warga</h3>
                <div class="flex justify-center" id="simpananChart"></div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-7 shadow-sm border border-slate-100 dark:border-slate-800">
                <h3 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest mb-6 border-b border-slate-50 dark:border-slate-800 pb-4">Tabungan Teratas (Top 5)</h3>
                
                <div class="space-y-4">
                    @forelse($accounts->sortByDesc('total_saldo')->take(5) as $acc)
                        <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800 rounded-2xl hover:ring-1 ring-amber-200 transition-all">
                            <div class="min-w-0">
                                <p class="text-[10px] font-black text-slate-800 dark:text-slate-200 truncate uppercase">{{ $acc->user->name }}</p>
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-tighter">Rumah {{ $acc->user->no_rumah }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-black text-amber-600 italic">Rp{{ number_format($acc->total_saldo, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-xs font-bold text-slate-400 italic py-6">Belum ada data tabungan.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Ambil data dari Controller
            var dataSimpanan = @json($chartSimpanan);
            
            var options = {
                series: dataSimpanan,
                chart: {
                    type: 'donut',
                    height: 350,
                    fontFamily: 'inherit',
                },
                labels: ['Simpanan Pokok', 'Simpanan Wajib', 'Simpanan Sukarela'],
                colors: ['#0f172a', '#3b82f6', '#10b981'], // Slate, Blue, Emerald
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                            labels: {
                                show: true,
                                name: { fontSize: '10px', fontWeight: 900 },
                                value: { 
                                    fontSize: '20px', 
                                    fontWeight: 900,
                                    formatter: function (val) {
                                        return "Rp " + new Intl.NumberFormat('id-ID').format(val);
                                    }
                                },
                                total: {
                                    show: true,
                                    label: 'TOTAL',
                                    fontSize: '12px',
                                    fontWeight: 900,
                                    formatter: function (w) {
                                        return "Rp " + new Intl.NumberFormat('id-ID').format(w.globals.seriesTotals.reduce((a, b) => { return a + b }, 0));
                                    }
                                }
                            }
                        }
                    }
                },
                dataLabels: { enabled: false },
                stroke: { width: 0 },
                legend: {
                    position: 'bottom',
                    fontSize: '11px',
                    fontWeight: 700,
                    markers: { radius: 12 }
                }
            };

            var chart = new ApexCharts(document.querySelector("#simpananChart"), options);
            chart.render();
        });
    </script>
</x-app-layout>