<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="text-xl font-black text-slate-800 dark:text-white tracking-tight leading-tight">
                Dashboard <span class="text-cyan-600">Ketua RT</span>
            </h2>
            <p class="text-[11px] text-slate-500 font-medium uppercase tracking-wider italic mt-1">Pusat Kendali & Pantauan Lingkungan</p>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-6">
        
        <div class="grid sm:grid-cols-3 gap-6">
            
            <div class="bg-gradient-to-br from-cyan-600 to-blue-700 p-6 rounded-3xl shadow-xl shadow-cyan-200/50 text-white relative overflow-hidden transition-all hover:-translate-y-1">
                <svg class="absolute -right-4 -top-4 opacity-10 size-24" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                <h3 class="text-[10px] font-black uppercase text-cyan-100 tracking-[0.2em]">Total Kepala Keluarga</h3>
                <h2 class="text-3xl font-black mt-2 tracking-tight">{{ $totalWarga }} <span class="text-xs font-bold opacity-60 uppercase">Warga</span></h2>
                <div class="mt-4 flex items-center gap-2">
                    <span class="text-[10px] bg-white/20 px-2 py-0.5 rounded-full font-bold">Terdata di Sistem</span>
                </div>
            </div>

            <div class="bg-gradient-to-br from-slate-800 to-slate-950 p-6 rounded-3xl shadow-xl shadow-slate-200/50 text-white relative overflow-hidden transition-all hover:-translate-y-1">
                <svg class="absolute -right-4 -top-4 opacity-10 size-24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <h3 class="text-[10px] font-black uppercase text-slate-400 tracking-[0.2em]">Saldo Kas RT Aktual</h3>
                <h2 class="text-3xl font-black mt-2 tracking-tight">Rp{{ number_format($saldoAktual, 0, ',', '.') }}</h2>
                <div class="mt-4 flex items-center gap-2">
                    <span class="size-2 bg-emerald-500 rounded-full animate-pulse"></span>
                    <span class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest">Kondisi Aman</span>
                </div>
            </div>

            <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 p-6 rounded-3xl shadow-xl shadow-indigo-200/50 text-white relative overflow-hidden transition-all hover:-translate-y-1">
                <svg class="absolute -right-4 -top-4 opacity-10 size-24" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <h3 class="text-[10px] font-black uppercase text-indigo-100 tracking-[0.2em]">Kepatuhan Iuran (Bulan Ini)</h3>
                <h2 class="text-3xl font-black mt-2 tracking-tight">{{ $persenKepatuhan }}%</h2>
                <div class="mt-4 w-full bg-white/10 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-indigo-400 h-full rounded-full transition-all duration-1000" style="width: {{ $persenKepatuhan }}%"></div>
                </div>
            </div>

        </div>

        <div class="grid lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-2 p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm rounded-3xl">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-[0.15em]">Statistik Partisipasi Warga</h2>
                        <p class="text-xs font-bold text-slate-400 mt-1">Jumlah KK yang Melunasi Iuran Per Bulan</p>
                    </div>
                </div>
                <div id="chart-rt-kepatuhan" class="w-full"></div>
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
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var options = {
                chart: { type: 'bar', height: 320, toolbar: { show: false }, fontFamily: 'Figtree, sans-serif' },
                series: [{ name: 'KK Lunas', data: @json($dataKepatuhan) }],
                colors: ['#0891b2'],
                plotOptions: {
                    bar: { borderRadius: 10, columnWidth: '40%', distributed: false, dataLabels: { position: 'top' } }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val) { return val + " KK"; },
                    offsetY: -20,
                    style: { fontSize: '10px', colors: ["#94a3b8"], fontWeight: 900 }
                },
                xaxis: { 
                    categories: @json($labelBulan),
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: { style: { colors: '#94a3b8', fontSize: '10px', fontWeight: 700 } }
                },
                yaxis: { show: false },
                grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
                tooltip: { theme: 'light', y: { formatter: (val) => val + " Kepala Keluarga" } }
            };
            
            new ApexCharts(document.querySelector("#chart-rt-kepatuhan"), options).render();
        });
    </script>
    @endpush
</x-app-layout>