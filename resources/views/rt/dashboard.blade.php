<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="text-xl font-black text-slate-800 dark:text-white tracking-tight leading-tight">
                Dashboard <span class="text-cyan-600">Ketua RT</span>
            </h2>
            <p class="text-[11px] text-slate-500 font-medium uppercase tracking-wider italic mt-1">Pusat Kendali & Pantauan Lingkungan</p>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8 max-w-[90rem] mx-auto space-y-6">
        
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            
            <div class="bg-gradient-to-br from-cyan-600 to-blue-700 p-4 sm:p-6 rounded-[1.25rem] sm:rounded-3xl shadow-lg sm:shadow-xl shadow-cyan-200/50 text-white relative overflow-hidden transition-all hover:-translate-y-1">
                <svg class="absolute -right-2 -top-2 sm:-right-4 sm:-top-4 opacity-10 size-16 sm:size-24" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                <h3 class="text-[8px] sm:text-[10px] font-black uppercase text-cyan-100 tracking-wider sm:tracking-[0.2em] line-clamp-1">Keluarga</h3>
                <h2 class="text-xl sm:text-3xl font-black mt-1 sm:mt-2 tracking-tight">{{ $totalWarga }} <span class="text-[9px] sm:text-xs font-bold opacity-60 uppercase">KK</span></h2>
                <div class="mt-2 sm:mt-4 flex items-center gap-1 sm:gap-2">
                    <span class="text-[8px] sm:text-[10px] bg-white/20 px-1.5 sm:px-2 py-0.5 rounded-full font-bold line-clamp-1">{{ $totalJiwa }} Jiwa</span>
                </div>
            </div>

            <div class="bg-gradient-to-br from-slate-800 to-slate-950 p-4 sm:p-6 rounded-[1.25rem] sm:rounded-3xl shadow-lg sm:shadow-xl shadow-slate-200/50 text-white relative overflow-hidden transition-all hover:-translate-y-1">
                <svg class="absolute -right-2 -top-2 sm:-right-4 sm:-top-4 opacity-10 size-16 sm:size-24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <h3 class="text-[8px] sm:text-[10px] font-black uppercase text-slate-400 tracking-wider sm:tracking-[0.2em] line-clamp-1">Saldo Kas RT</h3>
                <h2 class="text-xl sm:text-3xl font-black mt-1 sm:mt-2 tracking-tight">Rp{{ number_format($saldoAktual / 1000, 0, ',', '.') }}<span class="text-[10px] sm:hidden opacity-60">K</span><span class="hidden sm:inline">.000</span></h2>
                <div class="mt-2 sm:mt-4 flex items-center gap-1 sm:gap-2">
                    <span class="size-1.5 sm:size-2 bg-emerald-500 rounded-full animate-pulse shrink-0"></span>
                    <span class="text-[8px] sm:text-[10px] font-bold text-emerald-400 uppercase tracking-widest line-clamp-1">Kondisi Aman</span>
                </div>
            </div>

            <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 p-4 sm:p-6 rounded-[1.25rem] sm:rounded-3xl shadow-lg sm:shadow-xl shadow-indigo-200/50 text-white relative overflow-hidden transition-all hover:-translate-y-1">
                <svg class="absolute -right-2 -top-2 sm:-right-4 sm:-top-4 opacity-10 size-16 sm:size-24" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <h3 class="text-[8px] sm:text-[10px] font-black uppercase text-indigo-100 tracking-wider sm:tracking-[0.2em] line-clamp-1">Kepatuhan</h3>
                <h2 class="text-xl sm:text-3xl font-black mt-1 sm:mt-2 tracking-tight">{{ $persenKepatuhan }}%</h2>
                <div class="mt-2 sm:mt-4 w-full bg-white/10 h-1 sm:h-1.5 rounded-full overflow-hidden">
                    <div class="bg-indigo-400 h-full rounded-full transition-all duration-1000" style="width: {{ $persenKepatuhan }}%"></div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-rose-500 to-rose-700 p-4 sm:p-6 rounded-[1.25rem] sm:rounded-3xl shadow-lg sm:shadow-xl shadow-rose-200/50 text-white relative overflow-hidden transition-all hover:-translate-y-1">
                <svg class="absolute -right-2 -top-2 sm:-right-4 sm:-top-4 opacity-10 size-16 sm:size-24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <h3 class="text-[8px] sm:text-[10px] font-black uppercase text-rose-100 tracking-wider sm:tracking-[0.2em] line-clamp-1">Tunggakan</h3>
                <h2 class="text-xl sm:text-3xl font-black mt-1 sm:mt-2 tracking-tight">Rp{{ number_format($totalTunggakan / 1000, 0, ',', '.') }}<span class="text-[10px] sm:hidden opacity-60">K</span><span class="hidden sm:inline">.000</span></h2>
                <div class="mt-2 sm:mt-4 flex items-center gap-1 sm:gap-2">
                    <span class="text-[8px] sm:text-[10px] font-bold text-rose-200 uppercase tracking-widest line-clamp-1">Piutang RT</span>
                </div>
            </div>

        </div>

        <div class="grid lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-2 space-y-6">
                <!-- Tren Kas Area Chart -->
                <div class="p-4 sm:p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm rounded-[1.25rem] sm:rounded-3xl">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-[0.15em]">Tren Arus Kas (6 Bulan)</h2>
                            <p class="text-xs font-bold text-slate-400 mt-1">Perbandingan Pemasukan & Pengeluaran</p>
                        </div>
                    </div>
                    <div id="chart-rt-trenkas" class="w-full"></div>
                </div>

                <!-- Kepatuhan & Demografi (Grid 2 Kolom) -->
                <div class="grid sm:grid-cols-2 gap-6">
                    <!-- Kepatuhan Bar Chart -->
                    <div class="p-4 sm:p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm rounded-[1.25rem] sm:rounded-3xl flex flex-col">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h2 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-[0.15em]">Partisipasi Iuran</h2>
                                <p class="text-xs font-bold text-slate-400 mt-1">KK Melunasi Iuran</p>
                            </div>
                        </div>
                        <div class="flex-1 flex items-center justify-center">
                            <div id="chart-rt-kepatuhan" class="w-full"></div>
                        </div>
                    </div>

                    <!-- Demografi Umur Donut Chart -->
                    <div class="p-4 sm:p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm rounded-[1.25rem] sm:rounded-3xl flex flex-col">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h2 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-[0.15em]">Demografi Warga</h2>
                                <p class="text-xs font-bold text-slate-400 mt-1">Berdasarkan Kelompok Umur</p>
                            </div>
                        </div>
                        <div class="flex-1 flex items-center justify-center">
                            <div id="chart-rt-demografi" class="w-full"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm rounded-3xl flex flex-col">
                <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-50 dark:border-slate-800">
                    <h2 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-[0.15em]">Agenda Terdekat</h2>
                    <span class="flex size-6 items-center justify-center rounded-full bg-cyan-100 text-[10px] font-black text-cyan-600">{{ $agendaTerdekat->count() }}</span>
                </div>
                
                <div class="space-y-4 flex-1">
                    @forelse($agendaTerdekat as $agenda)
                        <div class="flex items-center gap-x-4 p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-transparent hover:border-cyan-200 dark:hover:border-cyan-900 transition-all group">
                            <div class="size-12 bg-white dark:bg-slate-800 shadow-sm rounded-2xl flex flex-col justify-center items-center shrink-0 border border-slate-100 dark:border-slate-700 group-hover:bg-cyan-600 group-hover:text-white transition-colors">
                                <span class="text-sm font-black leading-none">{{ \Carbon\Carbon::parse($agenda->tanggal)->format('d') }}</span>
                                <span class="text-[8px] font-bold uppercase tracking-widest mt-1">{{ \Carbon\Carbon::parse($agenda->tanggal)->translatedFormat('M') }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-black text-slate-800 dark:text-slate-200 truncate">{{ $agenda->nama_agenda ?? $agenda->judul }}</p>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5 truncate">{{ $agenda->lokasi ?? 'Wilayah RT' }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic leading-relaxed">Tidak ada agenda dalam waktu dekat.</p>
                        </div>
                    @endforelse
                </div>
                
                <a href="{{ route('agendas.index') }}" class="block w-full text-center mt-6 py-3 bg-cyan-600 hover:bg-cyan-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-md shadow-cyan-200 dark:shadow-none active:scale-95">
                    Buka Semua Agenda
                </a>

                <div class="mt-8 border-t border-slate-100 dark:border-slate-800 pt-6">
                    <h2 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-[0.15em] mb-4">Warga Tunggakan Tertinggi</h2>
                    <div class="space-y-3">
                        @forelse($topTunggakan as $warga)
                            <div class="flex items-center justify-between p-3 rounded-xl bg-rose-50 dark:bg-rose-900/10 border border-rose-100 dark:border-rose-900/30">
                                <div class="min-w-0">
                                    <p class="text-xs font-black text-slate-800 dark:text-slate-200 truncate">{{ $warga->name }}</p>
                                    <p class="text-[9px] font-bold text-slate-400 mt-0.5 truncate">{{ $warga->blok_rumah }} / {{ $warga->no_rumah }}</p>
                                </div>
                                <span class="text-xs font-black text-rose-600 dark:text-rose-400">Rp{{ number_format($warga->total_tunggakan, 0, ',', '.') }}</span>
                            </div>
                        @empty
                            <div class="text-center py-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl border border-emerald-100 dark:border-emerald-900/30">
                                <p class="text-xs font-bold text-emerald-600 dark:text-emerald-400">Tidak ada tunggakan! 🎉</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isMobile = window.innerWidth < 1024;
            
            var optKepatuhan = {
                chart: { type: 'bar', height: isMobile ? 200 : 280, toolbar: { show: false }, fontFamily: 'Figtree, sans-serif' },
                series: [{ name: 'KK Lunas', data: @json($dataKepatuhan) }],
                colors: ['#0891b2'],
                plotOptions: {
                    bar: { borderRadius: 8, columnWidth: '40%', dataLabels: { position: 'top' } }
                },
                dataLabels: {
                    enabled: true, formatter: function (val) { return val + " KK"; },
                    offsetY: -20, style: { fontSize: '10px', colors: ["#94a3b8"], fontWeight: 900 }
                },
                xaxis: { 
                    categories: @json($labelBulan), axisBorder: { show: false }, axisTicks: { show: false },
                    labels: { style: { colors: '#94a3b8', fontSize: '10px', fontWeight: 700 } }
                },
                yaxis: { show: false },
                grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
                tooltip: { theme: 'light', y: { formatter: (val) => val + " Kepala Keluarga" } }
            };
            new ApexCharts(document.querySelector("#chart-rt-kepatuhan"), optKepatuhan).render();

            var optTrenKas = {
                chart: { type: 'area', height: isMobile ? 220 : 320, toolbar: { show: false }, fontFamily: 'Figtree, sans-serif' },
                series: [
                    { name: 'Pemasukan', data: @json($dataPemasukan) },
                    { name: 'Pengeluaran', data: @json($dataPengeluaran) }
                ],
                colors: ['#10b981', '#f43f5e'],
                fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 100] } },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
                xaxis: { 
                    categories: @json($labelBulan), axisBorder: { show: false }, axisTicks: { show: false },
                    labels: { style: { colors: '#94a3b8', fontSize: '10px', fontWeight: 700 } }
                },
                yaxis: { 
                    labels: { style: { colors: '#94a3b8', fontSize: '10px', fontWeight: 700 }, formatter: (value) => "Rp" + (value/1000) + "K" } 
                },
                grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
                tooltip: { theme: 'light', y: { formatter: (val) => "Rp" + val.toLocaleString('id-ID') } }
            };
            new ApexCharts(document.querySelector("#chart-rt-trenkas"), optTrenKas).render();

            var optDemografi = {
                series: @json($dataDemografi),
                labels: ['Balita (0-5)', 'Anak-anak (6-12)', 'Remaja (13-17)', 'Dewasa (18-59)', 'Lansia (60+)'],
                chart: { type: 'donut', height: isMobile ? 220 : 280, fontFamily: 'Figtree, sans-serif' },
                colors: ['#0ea5e9', '#8b5cf6', '#ec4899', '#10b981', '#f59e0b'],
                plotOptions: {
                    pie: {
                        donut: { size: '65%', labels: { show: true, name: { fontSize: '10px', fontWeight: 900 }, value: { fontSize: '20px', fontWeight: 900 } } }
                    }
                },
                dataLabels: { enabled: false },
                legend: { position: 'bottom', fontSize: '10px', fontWeight: 800, markers: { radius: 12 } }
            };
            new ApexCharts(document.querySelector("#chart-rt-demografi"), optDemografi).render();
        });
    </script>
    @endpush
</x-app-layout>