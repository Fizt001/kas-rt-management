<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="text-xl font-black text-slate-800 dark:text-white tracking-tight leading-tight">
                Dashboard <span class="text-emerald-600">Pengurus Mesjid</span>
            </h2>
            <p class="text-[11px] text-slate-500 font-medium uppercase tracking-wider italic mt-1">Ringkasan Aktivitas Keuangan DKM</p>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        
        <!-- 3 KARTU METRIK UTAMA -->
        <div class="grid sm:grid-cols-3 gap-4 sm:gap-6 mb-8">
            
            <div class="flex flex-col bg-gradient-to-br from-emerald-600 to-teal-700 shadow-xl shadow-emerald-200/50 rounded-3xl dark:shadow-none transition-all duration-300 hover:-translate-y-1">
                <div class="p-6 flex justify-between items-center relative overflow-hidden">
                    <svg class="absolute -right-4 -top-4 opacity-10 size-24 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.64-2.25 1.64-1.74 0-2.26-.95-2.32-1.81H7.84c.09 1.71 1.25 2.85 3.06 3.2V19h2.33v-1.66c1.64-.32 2.92-1.38 2.92-3.03 0-2.19-1.76-2.8-3.84-3.17z"/></svg>
                    <div class="relative z-10">
                        <h3 class="text-[10px] font-black uppercase text-emerald-100 tracking-[0.2em]">Saldo Kas Mesjid</h3>
                        <div class="mt-2">
                            <h3 class="text-2xl font-black text-white tracking-tight drop-shadow-sm">Rp {{ number_format($saldoAktual ?? 0, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                    <div class="relative z-10 size-12 bg-white/20 backdrop-blur-md text-white rounded-2xl flex justify-center items-center border border-white/30 shadow-inner">
                        <svg class="size-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                </div>
            </div>

            <div class="flex flex-col bg-gradient-to-br from-blue-500 to-indigo-600 shadow-xl shadow-blue-200/50 rounded-3xl dark:shadow-none transition-all duration-300 hover:-translate-y-1">
                <div class="p-6 flex justify-between items-center relative overflow-hidden">
                    <svg class="absolute -right-4 -top-4 opacity-10 size-24 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    <div class="relative z-10">
                        <h3 class="text-[10px] font-black uppercase text-blue-100 tracking-[0.2em]">Infaq Bulan Ini</h3>
                        <div class="mt-2 flex items-baseline gap-2">
                            <h3 class="text-2xl font-black text-white tracking-tight drop-shadow-sm">Rp {{ number_format($masukBulanIni ?? 0, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                    <div class="relative z-10 size-12 bg-white/20 backdrop-blur-md text-white rounded-2xl flex justify-center items-center border border-white/30 shadow-inner">
                        <svg class="size-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    </div>
                </div>
            </div>

            <div class="flex flex-col bg-gradient-to-br from-amber-500 to-orange-600 shadow-xl shadow-amber-200/50 rounded-3xl dark:shadow-none transition-all duration-300 hover:-translate-y-1">
                <div class="p-6 flex justify-between items-center relative overflow-hidden">
                    <svg class="absolute -right-4 -top-4 opacity-10 size-24 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div class="relative z-10">
                        <h3 class="text-[10px] font-black uppercase text-amber-100 tracking-[0.2em]">Pengeluaran Bulan Ini</h3>
                        <div class="mt-2">
                            <h3 class="text-2xl font-black text-white tracking-tight drop-shadow-sm">Rp {{ number_format($keluarBulanIni ?? 0, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                    <div class="relative z-10 size-12 bg-white/20 backdrop-blur-md text-white rounded-2xl flex justify-center items-center border border-white/30 shadow-inner">
                        <svg class="size-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                </div>
            </div>

        </div>

        <div class="grid lg:grid-cols-3 gap-6">
            
            <!-- AREA CHART -->
            <div class="lg:col-span-2 p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm rounded-3xl">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-[0.15em]">Statistik Kas Mesjid</h2>
                        <p class="text-xs font-bold text-slate-400 mt-1">Tren Arus Kas 7 Hari Terakhir</p>
                    </div>
                    <span class="bg-emerald-50 text-emerald-600 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border border-emerald-100">7 Hari</span>
                </div>
                <div id="chart-mesjid-main" class="w-full"></div>
            </div>

            <!-- AKTIVITAS TERKINI (LOOP DARI DATABASE) -->
            <div class="p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm rounded-3xl flex flex-col">
                <h2 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-[0.15em] mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">Aktivitas Terkini</h2>
                
                <div class="space-y-5 flex-1">
                    @forelse($aktivitas as $item)
                        <div class="flex gap-x-4">
                            <!-- Kondisi Warna Ikon: Hijau (Infaq), Amber (Keluar) -->
                            <div class="size-10 rounded-2xl flex justify-center items-center shrink-0 
                                {{ $item['type'] == 'infaq' ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400' }}">
                                @if($item['type'] == 'infaq')
                                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                @else
                                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/></svg>
                                @endif
                            </div>
                            
                            <div>
                                <p class="text-xs font-black text-slate-800 dark:text-slate-200">{{ $item['judul'] }}</p>
                                <p class="text-[10px] font-bold text-slate-400 italic mt-0.5">
                                    <span class="{{ $item['type'] == 'infaq' ? 'text-emerald-500' : 'text-amber-500' }}">
                                        {{ $item['type'] == 'infaq' ? '+' : '-' }}Rp {{ number_format($item['nominal'], 0, ',', '.') }}
                                    </span> 
                                    • {{ \Carbon\Carbon::parse($item['waktu'])->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">Belum ada aktivitas.</p>
                        </div>
                    @endforelse
                </div>
                
                <a href="{{ route('mesjid.laporan') }}" class="block w-full text-center mt-4 py-3 bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl text-[10px] font-black uppercase tracking-widest transition-colors">
                    Lihat Laporan Lengkap
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var options = {
                chart: { 
                    type: 'area', 
                    height: 320, 
                    toolbar: { show: false }, 
                    fontFamily: 'Figtree, sans-serif',
                    zoom: { enabled: false }
                },
                series: [
                    { name: 'Infaq Masuk', data: @json($dataMasuk) },
                    { name: 'Pengeluaran', data: @json($dataKeluar) }
                ],
                colors: ['#059669', '#f59e0b'],
                fill: { 
                    type: 'gradient', 
                    gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 100] } 
                },
                stroke: { curve: 'smooth', width: 3 },
                xaxis: { 
                    categories: @json($labelHari), 
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: { style: { colors: '#94a3b8', fontSize: '10px', fontWeight: 700 } }
                },
                yaxis: { 
                    labels: { 
                        formatter: (val) => {
                            if(val >= 1000) return 'Rp ' + (val/1000).toLocaleString('id-ID') + 'k';
                            return 'Rp ' + val;
                        },
                        style: { colors: '#94a3b8', fontSize: '10px', fontWeight: 700 } 
                    } 
                },
                grid: { 
                    borderColor: '#f1f5f9',
                    strokeDashArray: 4,
                    padding: { top: 0, right: 0, bottom: 0, left: 10 }
                },
                dataLabels: { enabled: false },
                legend: { 
                    position: 'top', 
                    horizontalAlign: 'right',
                    fontSize: '11px',
                    fontWeight: 700,
                    markers: { radius: 12 }
                },
                tooltip: {
                    theme: 'light',
                    y: { formatter: function (val) { return "Rp " + val.toLocaleString('id-ID') } }
                }
            };
            
            var chart = new ApexCharts(document.querySelector("#chart-mesjid-main"), options);
            chart.render();
        });
    </script>
    @endpush
</x-app-layout>