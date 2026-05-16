<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="text-xl font-black text-slate-800 dark:text-white tracking-tight leading-tight">
                Dashboard <span class="text-indigo-600">Bendahara RT</span>
            </h2>
            <p class="text-[11px] text-slate-500 font-medium uppercase tracking-wider italic mt-1">Pusat Kendali Keuangan & Verifikasi Iuran</p>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        
        <div class="grid sm:grid-cols-3 gap-4 sm:gap-6 mb-8">
            
            <div class="flex flex-col bg-gradient-to-br from-indigo-600 to-blue-700 shadow-xl shadow-indigo-200/50 rounded-3xl dark:shadow-none transition-all duration-300 hover:-translate-y-1">
                <div class="p-6 flex justify-between items-center relative overflow-hidden">
                    <svg class="absolute -right-4 -top-4 opacity-10 size-24 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.64-2.25 1.64-1.74 0-2.26-.95-2.32-1.81H7.84c.09 1.71 1.25 2.85 3.06 3.2V19h2.33v-1.66c1.64-.32 2.92-1.38 2.92-3.03 0-2.19-1.76-2.8-3.84-3.17z"/></svg>
                    <div class="relative z-10">
                        <h3 class="text-[10px] font-black uppercase text-indigo-100 tracking-[0.2em]">Saldo Kas RT</h3>
                        <div class="mt-2">
                            <h3 class="text-2xl font-black text-white tracking-tight drop-shadow-sm">Rp {{ number_format($saldoAktual ?? 0, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                    <div class="relative z-10 size-12 bg-white/20 backdrop-blur-md text-white rounded-2xl flex justify-center items-center border border-white/30 shadow-inner">
                        <svg class="size-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                </div>
            </div>

            <div class="flex flex-col bg-gradient-to-br from-amber-500 to-orange-600 shadow-xl shadow-amber-200/50 rounded-3xl dark:shadow-none transition-all duration-300 hover:-translate-y-1">
                <div class="p-6 flex justify-between items-center relative overflow-hidden">
                    <svg class="absolute -right-4 -top-4 opacity-10 size-24 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div class="relative z-10">
                        <h3 class="text-[10px] font-black uppercase text-amber-100 tracking-[0.2em]">Butuh Verifikasi</h3>
                        <div class="mt-2 flex items-baseline gap-2">
                            <h3 class="text-2xl font-black text-white tracking-tight drop-shadow-sm">{{ $pendingVerifikasi ?? 0 }} Struk</h3>
                            @if(($pendingVerifikasi ?? 0) > 0)
                            <span class="text-[9px] font-black text-amber-600 bg-white px-2 py-0.5 rounded-full shadow-sm animate-pulse uppercase">Segera!</span>
                            @endif
                        </div>
                    </div>
                    <div class="relative z-10 size-12 bg-white/20 backdrop-blur-md text-white rounded-2xl flex justify-center items-center border border-white/30 shadow-inner">
                        <svg class="size-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                </div>
            </div>

            <div class="flex flex-col bg-gradient-to-br from-rose-500 to-pink-600 shadow-xl shadow-rose-200/50 rounded-3xl dark:shadow-none transition-all duration-300 hover:-translate-y-1">
                <div class="p-6 flex justify-between items-center relative overflow-hidden">
                    <svg class="absolute -right-4 -top-4 opacity-10 size-24 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div class="relative z-10">
                        <h3 class="text-[10px] font-black uppercase text-rose-100 tracking-[0.2em]">Total Tunggakan</h3>
                        <div class="mt-2">
                            <h3 class="text-2xl font-black text-white tracking-tight drop-shadow-sm">Rp {{ number_format($totalTunggakan ?? 0, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                    <div class="relative z-10 size-12 bg-white/20 backdrop-blur-md text-white rounded-2xl flex justify-center items-center border border-white/30 shadow-inner">
                        <svg class="size-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>

        </div>

        <div class="grid lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-2 p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm rounded-3xl">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-[0.15em]">Statistik Keuangan RT</h2>
                        <p class="text-xs font-bold text-slate-400 mt-1">Pemasukan vs Pengeluaran 6 Bulan Terakhir</p>
                    </div>
                    <span class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border border-indigo-100">Semester Ini</span>
                </div>
                <div id="chart-bendahara-main" class="w-full"></div>
            </div>

            <div class="p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm rounded-3xl flex flex-col">
                <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                    <h2 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-[0.15em]">Antrean Verifikasi</h2>
                    <span class="size-6 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center text-[10px] font-black">{{ $pendingVerifikasi ?? 0 }}</span>
                </div>
                
                <div class="space-y-4 flex-1">
                    @forelse($antreanVerifikasi ?? [] as $item)
                        <div class="flex items-center gap-x-4 p-2 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <div class="size-10 bg-indigo-100 text-indigo-600 rounded-2xl flex justify-center items-center shrink-0 dark:bg-indigo-900/30 dark:text-indigo-400">
                                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-black text-slate-800 dark:text-slate-200 truncate">{{ $item->user->name ?? 'Warga' }}</p>
                                <p class="text-[10px] font-bold text-slate-400 italic mt-0.5">Rp {{ number_format($item->total_amount ?? 0, 0, ',', '.') }} • {{ $item->updated_at->diffForHumans() }}</p>
                            </div>
                            <a href="{{ route('verifikasi.index') }}" class="size-8 rounded-xl bg-indigo-50 hover:bg-indigo-600 text-indigo-600 hover:text-white flex items-center justify-center transition-colors shadow-sm">
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">Antrean Kosong.</p>
                        </div>
                    @endforelse
                </div>
                
                @if(($pendingVerifikasi ?? 0) > 0)
                <a href="{{ route('verifikasi.index') }}" class="block w-full text-center mt-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-colors shadow-md shadow-indigo-200 dark:shadow-none">
                    Verifikasi Sekarang
                </a>
                @endif
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
                    { name: 'Pemasukan', data: @json($dataPemasukan) },
                    { name: 'Pengeluaran', data: @json($dataPengeluaran) }
                ],
                colors: ['#4f46e5', '#f43f5e'], // Indigo & Rose
                fill: { 
                    type: 'gradient', 
                    gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.05, stops: [0, 100] } 
                },
                stroke: { curve: 'smooth', width: 3 },
                xaxis: { 
                    categories: @json($labelBulan), 
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: { style: { colors: '#94a3b8', fontSize: '10px', fontWeight: 700 } }
                },
                yaxis: { 
                    labels: { 
                        formatter: (val) => {
                            if(val >= 1000000) return 'Rp ' + (val/1000000).toFixed(1).replace('.0', '') + 'jt';
                            if(val >= 1000) return 'Rp ' + (val/1000) + 'k';
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
            
            var chart = new ApexCharts(document.querySelector("#chart-bendahara-main"), options);
            chart.render();
        });
    </script>
    @endpush
</x-app-layout>