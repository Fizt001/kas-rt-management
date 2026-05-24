<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="text-xl font-black text-slate-800 dark:text-white tracking-tight leading-tight">
                Dashboard <span class="text-indigo-600">Bendahara RT</span>
            </h2>
            <p class="text-[11px] text-slate-500 font-medium uppercase tracking-wider italic mt-1">Pusat Kendali Keuangan & Verifikasi Iuran</p>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8 max-w-[90rem] mx-auto space-y-6">
        
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-8">
            
            <div class="bg-gradient-to-br from-indigo-600 to-blue-700 p-4 sm:p-6 rounded-[1.25rem] sm:rounded-3xl shadow-lg sm:shadow-xl shadow-indigo-200/50 text-white relative overflow-hidden transition-all hover:-translate-y-1">
                <svg class="absolute -right-2 -top-2 sm:-right-4 sm:-top-4 opacity-10 size-16 sm:size-24 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.64-2.25 1.64-1.74 0-2.26-.95-2.32-1.81H7.84c.09 1.71 1.25 2.85 3.06 3.2V19h2.33v-1.66c1.64-.32 2.92-1.38 2.92-3.03 0-2.19-1.76-2.8-3.84-3.17z"/></svg>
                <h3 class="text-[8px] sm:text-[10px] font-black uppercase text-indigo-100 tracking-wider sm:tracking-[0.2em] line-clamp-1">Saldo Kas RT</h3>
                <h2 class="text-xl sm:text-3xl font-black mt-1 sm:mt-2 tracking-tight">Rp{{ number_format($saldoAktual / 1000, 0, ',', '.') }}<span class="text-[10px] sm:hidden opacity-60">K</span><span class="hidden sm:inline">.000</span></h2>
                <div class="mt-2 sm:mt-4 flex items-center gap-1 sm:gap-2">
                    <span class="text-[8px] sm:text-[10px] font-bold text-indigo-200 uppercase tracking-widest line-clamp-1">Kondisi Aman</span>
                </div>
            </div>

            <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-4 sm:p-6 rounded-[1.25rem] sm:rounded-3xl shadow-lg sm:shadow-xl shadow-emerald-200/50 text-white relative overflow-hidden transition-all hover:-translate-y-1">
                <svg class="absolute -right-2 -top-2 sm:-right-4 sm:-top-4 opacity-10 size-16 sm:size-24 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <h3 class="text-[8px] sm:text-[10px] font-black uppercase text-emerald-100 tracking-wider sm:tracking-[0.2em] line-clamp-1">Pemasukan</h3>
                <h2 class="text-xl sm:text-3xl font-black mt-1 sm:mt-2 tracking-tight">Rp{{ number_format($pemasukanBulanIni / 1000 ?? 0, 0, ',', '.') }}<span class="text-[10px] sm:hidden opacity-60">K</span><span class="hidden sm:inline">.000</span></h2>
                <div class="mt-2 sm:mt-4 flex items-center gap-1 sm:gap-2">
                    <span class="text-[8px] sm:text-[10px] font-bold text-emerald-200 uppercase tracking-widest line-clamp-1">Bulan Ini</span>
                </div>
            </div>

            <div class="bg-gradient-to-br from-amber-500 to-orange-600 p-4 sm:p-6 rounded-[1.25rem] sm:rounded-3xl shadow-lg sm:shadow-xl shadow-amber-200/50 text-white relative overflow-hidden transition-all hover:-translate-y-1">
                <svg class="absolute -right-2 -top-2 sm:-right-4 sm:-top-4 opacity-10 size-16 sm:size-24 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <h3 class="text-[8px] sm:text-[10px] font-black uppercase text-amber-100 tracking-wider sm:tracking-[0.2em] line-clamp-1">Verifikasi</h3>
                <h2 class="text-xl sm:text-3xl font-black mt-1 sm:mt-2 tracking-tight">{{ $pendingVerifikasi ?? 0 }} <span class="text-[9px] sm:text-xs font-bold opacity-60 uppercase">Struk</span></h2>
                <div class="mt-2 sm:mt-4 flex items-center gap-1 sm:gap-2">
                    @if(($pendingVerifikasi ?? 0) > 0)
                    <span class="text-[8px] sm:text-[9px] w-fit font-black text-amber-600 bg-white px-2 py-0.5 rounded-full shadow-sm animate-pulse uppercase">Segera!</span>
                    @else
                    <span class="text-[8px] sm:text-[10px] font-bold text-amber-200 uppercase tracking-widest line-clamp-1">Semua Beres</span>
                    @endif
                </div>
            </div>

            <div class="bg-gradient-to-br from-rose-500 to-pink-600 p-4 sm:p-6 rounded-[1.25rem] sm:rounded-3xl shadow-lg sm:shadow-xl shadow-rose-200/50 text-white relative overflow-hidden transition-all hover:-translate-y-1">
                <svg class="absolute -right-2 -top-2 sm:-right-4 sm:-top-4 opacity-10 size-16 sm:size-24 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <h3 class="text-[8px] sm:text-[10px] font-black uppercase text-rose-100 tracking-wider sm:tracking-[0.2em] line-clamp-1">Tunggakan</h3>
                <h2 class="text-xl sm:text-3xl font-black mt-1 sm:mt-2 tracking-tight">Rp{{ number_format($totalTunggakan / 1000 ?? 0, 0, ',', '.') }}<span class="text-[10px] sm:hidden opacity-60">K</span><span class="hidden sm:inline">.000</span></h2>
                <div class="mt-2 sm:mt-4 flex items-center gap-1 sm:gap-2">
                    <span class="text-[8px] sm:text-[10px] font-bold text-rose-200 uppercase tracking-widest line-clamp-1">Piutang Kas</span>
                </div>
            </div>

        </div>

        <div class="grid lg:grid-cols-3 gap-6 mb-8">
            
            <div class="lg:col-span-2 p-4 sm:p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm rounded-[1.25rem] sm:rounded-3xl">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-[0.15em]">Statistik Keuangan RT</h2>
                        <p class="text-[10px] sm:text-xs font-bold text-slate-400 mt-1">Pemasukan vs Pengeluaran 6 Bulan Terakhir</p>
                    </div>
                    <span class="bg-indigo-50 text-indigo-600 px-2 sm:px-3 py-1 rounded-lg text-[8px] sm:text-[10px] font-black uppercase tracking-widest border border-indigo-100">Semester Ini</span>
                </div>
                <div id="chart-bendahara-main" class="w-full"></div>
            </div>

            <div class="p-4 sm:p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm rounded-[1.25rem] sm:rounded-3xl flex flex-col">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-[0.15em]">Status Iuran</h2>
                        <p class="text-[10px] sm:text-xs font-bold text-slate-400 mt-1">Bulan Ini</p>
                    </div>
                </div>
                <div class="flex-1 flex items-center justify-center">
                    <div id="chart-status-iuran" class="w-full"></div>
                </div>
            </div>

        </div>

        <div class="grid lg:grid-cols-2 gap-6">

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

            <div class="p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm rounded-3xl flex flex-col">
                <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                    <h2 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-[0.15em]">Riwayat Pengeluaran</h2>
                    <span class="size-6 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-[10px] font-black">{{ count($riwayatPengeluaran) }}</span>
                </div>
                
                <div class="space-y-4 flex-1">
                    @forelse($riwayatPengeluaran as $agenda)
                        <div class="flex items-center gap-x-4 p-2 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <div class="size-10 bg-emerald-100 text-emerald-600 rounded-2xl flex justify-center items-center shrink-0 dark:bg-emerald-900/30 dark:text-emerald-400">
                                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-black text-slate-800 dark:text-slate-200 truncate">{{ $agenda->nama_agenda ?? $agenda->judul }}</p>
                                <p class="text-[10px] font-bold text-slate-400 italic mt-0.5">{{ \Carbon\Carbon::parse($agenda->tanggal)->translatedFormat('d M Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-black text-rose-600 dark:text-rose-400">-Rp {{ number_format($agenda->realisasi_dana ?? 0, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">Belum ada pengeluaran.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isMobile = window.innerWidth < 1024;
            
            var optMain = {
                chart: { 
                    type: 'area', 
                    height: isMobile ? 220 : 320, 
                    toolbar: { show: false }, 
                    fontFamily: 'Figtree, sans-serif',
                    zoom: { enabled: false }
                },
                series: [
                    { name: 'Pemasukan', data: @json($dataPemasukan) },
                    { name: 'Pengeluaran', data: @json($dataPengeluaranChart) }
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
            
            new ApexCharts(document.querySelector("#chart-bendahara-main"), optMain).render();

            var optStatus = {
                series: @json($dataStatusIuran),
                labels: ['Lunas', 'Pending', 'Belum Lunas'],
                chart: { type: 'donut', height: isMobile ? 220 : 280, fontFamily: 'Figtree, sans-serif' },
                colors: ['#10b981', '#f59e0b', '#f43f5e'],
                plotOptions: {
                    pie: {
                        donut: { size: '70%', labels: { show: true, name: { fontSize: '10px', fontWeight: 900 }, value: { fontSize: '24px', fontWeight: 900 } } }
                    }
                },
                dataLabels: { enabled: false },
                legend: { position: 'bottom', fontSize: '10px', fontWeight: 800, markers: { radius: 12 } }
            };
            new ApexCharts(document.querySelector("#chart-status-iuran"), optStatus).render();
        });
    </script>
    @endpush
</x-app-layout>