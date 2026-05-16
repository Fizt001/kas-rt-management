<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="text-xl font-black text-slate-800 dark:text-white tracking-tight">
                Halo, <span class="text-indigo-600">{{ Auth::user()->name }}</span> 👋
            </h2>
            <p class="text-[11px] text-slate-500 font-medium uppercase tracking-wider italic mt-1">Portal Warga KAS-RT Digital</p>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 max-w-7xl mx-auto space-y-6">
        
        <div class="relative overflow-hidden bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 shadow-sm p-6">
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-4">
                    @if($statusBulanIni && $statusBulanIni->status == 'lunas')
                        <div class="size-14 bg-emerald-100 text-emerald-600 rounded-[1.5rem] flex items-center justify-center shadow-inner shrink-0">
                            <svg class="size-8" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-slate-800 dark:text-white italic tracking-tight">Iuran {{ Carbon\Carbon::now()->translatedFormat('F') }} Lunas</h2>
                            <p class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest">Kontribusi Anda telah tercatat dengan baik</p>
                        </div>
                    @else
                        <div class="size-14 bg-rose-100 text-rose-600 rounded-[1.5rem] flex items-center justify-center animate-pulse shrink-0">
                            <svg class="size-8" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-slate-800 dark:text-white italic tracking-tight">Iuran Belum Terbayar</h2>
                            <p class="text-[10px] font-bold text-rose-500 uppercase tracking-widest">Segera selesaikan tagihan rutin bulan ini</p>
                        </div>
                    @endif
                </div>
                <a href="{{ route('warga.iuran') }}" class="px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all shadow-xl shadow-indigo-200 dark:shadow-none active:scale-95">
                    Rincian Tagihan
                </a>
            </div>
        </div>

        <div class="grid lg:grid-cols-10 gap-6">
            
            <div class="lg:col-span-7">
                <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 p-8 shadow-sm h-full">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4 border-b border-slate-50 dark:border-slate-800 pb-6">
                        <div>
                            <h3 class="text-base font-black text-slate-800 dark:text-white uppercase tracking-widest leading-none">Transparansi Keuangan</h3>
                            <p class="text-[9px] font-bold text-slate-400 mt-2 uppercase tracking-tighter">Riwayat 6 Bulan: Kontribusi Saya vs Pengeluaran Lingkungan</p>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex items-center gap-2">
                                <div class="size-3 bg-indigo-600 rounded-full"></div>
                                <span class="text-[9px] font-black text-slate-500 uppercase">Pembayaran Saya</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="size-3 bg-rose-500 rounded-full"></div>
                                <span class="text-[9px] font-black text-slate-500 uppercase">Kas Keluar RT</span>
                            </div>
                        </div>
                    </div>
                    <div id="chart-warga-transparansi"></div>
                </div>
            </div>

            <div class="lg:col-span-3 space-y-6">
                
                <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-100 dark:border-slate-800 p-6 shadow-sm">
                    <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-50 dark:border-slate-800">
                        <h3 class="text-[10px] font-black text-slate-800 dark:text-white uppercase tracking-widest">Agenda RT</h3>
                        <span class="text-[8px] font-black text-indigo-600 bg-indigo-50 dark:bg-indigo-900/30 px-2.5 py-1 rounded-full uppercase">Terdekat</span>
                    </div>
                    <div class="space-y-4">
                        @forelse($agendaTerdekat as $agenda)
                            <div class="flex items-center gap-3 group cursor-pointer">
                                <div class="size-11 bg-slate-50 dark:bg-slate-800 group-hover:bg-indigo-50 dark:group-hover:bg-indigo-900/20 rounded-xl flex flex-col items-center justify-center shrink-0 border border-slate-100 dark:border-slate-700 transition-colors">
                                    <span class="text-xs font-black leading-none text-slate-700 dark:text-slate-200">{{ \Carbon\Carbon::parse($agenda->tanggal)->format('d') }}</span>
                                    <span class="text-[7px] font-black uppercase text-indigo-500">{{ \Carbon\Carbon::parse($agenda->tanggal)->translatedFormat('M') }}</span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[11px] font-black text-slate-800 dark:text-white truncate uppercase tracking-tight">{{ $agenda->nama_agenda ?? $agenda->judul }}</p>
                                    <p class="text-[9px] font-medium text-slate-400 truncate italic tracking-tighter">{{ $agenda->lokasi ?? 'Lingkungan RT' }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-[10px] font-bold text-slate-300 italic text-center py-4 uppercase">Belum ada agenda</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-[2.5rem] p-6 text-white shadow-xl shadow-emerald-200 dark:shadow-none group relative overflow-hidden">
                    <svg class="absolute -right-4 -bottom-4 opacity-20 size-24 transform group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    <div class="relative z-10">
                        <p class="text-[9px] font-black uppercase text-emerald-100 tracking-widest mb-1">Amal Jariyah Saya</p>
                        <h4 class="text-2xl font-black">Rp{{ number_format($totalInfaq ?? 0, 0, ',', '.') }}</h4>
                        <div class="mt-4">
                            <a href="{{ route('warga.infaq') }}" class="text-[8px] font-black uppercase bg-white/20 hover:bg-white/30 px-5 py-2 rounded-full transition-all inline-block border border-white/10 tracking-widest">Tunaikan Infaq</a>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-[2.5rem] p-6 text-white shadow-xl shadow-amber-200 dark:shadow-none group relative overflow-hidden">
                    <svg class="absolute -right-4 -bottom-4 opacity-20 size-24 transform group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div class="relative z-10">
                        <p class="text-[9px] font-black uppercase text-amber-100 tracking-widest mb-1">Tabungan Koperasi</p>
                        <h4 class="text-2xl font-black">Rp{{ number_format($totalTabungan ?? 0, 0, ',', '.') }}</h4>
                        <div class="mt-4">
                            <a href="{{ route('warga.koperasi') }}" class="text-[8px] font-black uppercase bg-white/20 hover:bg-white/30 px-5 py-2 rounded-full transition-all inline-block border border-white/10 tracking-widest">Akses Koperasi</a>
                        </div>
                    </div>
                </div>

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
                    height: 380, 
                    toolbar: { show: false }, 
                    fontFamily: 'inherit', 
                    zoom: { enabled: false }
                },
                series: [
                    { name: 'Bayaran Saya', data: @json($dataBayarSaya) },
                    { name: 'Pengeluaran RT', data: @json($dataPengeluaranRT) }
                ],
                colors: ['#4f46e5', '#f43f5e'],
                fill: { 
                    type: 'gradient', 
                    gradient: { 
                        shadeIntensity: 1, 
                        opacityFrom: 0.4, 
                        opacityTo: 0.05, 
                        stops: [0, 100] 
                    } 
                },
                stroke: { curve: 'smooth', width: 4 },
                xaxis: { 
                    categories: @json($labelBulan),
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: { 
                        style: { 
                            colors: '#94a3b8', 
                            fontSize: '10px', 
                            fontWeight: 700, 
                            cssClass: 'uppercase tracking-widest' 
                        } 
                    }
                },
                yaxis: { 
                    labels: { 
                        formatter: (val) => {
                            if(val >= 1000000) return 'Rp ' + (val/1000000).toFixed(1) + 'jt';
                            if(val >= 1000) return 'Rp ' + (val/1000) + 'rb';
                            return 'Rp ' + val;
                        },
                        style: { colors: '#94a3b8', fontSize: '10px', fontWeight: 700 } 
                    } 
                },
                grid: { borderColor: '#f1f5f9', strokeDashArray: 4, padding: { left: 10, right: 10 } },
                dataLabels: { enabled: false },
                markers: { size: 4, colors: ['#4f46e5', '#f43f5e'], strokeWidth: 2, strokeColors: '#fff', hover: { size: 6 } },
                legend: { show: false },
                tooltip: { 
                    theme: 'light', 
                    y: { formatter: (val) => "Rp " + val.toLocaleString('id-ID') } 
                }
            };
            new ApexCharts(document.querySelector("#chart-warga-transparansi"), options).render();
        });
    </script>
    @endpush
</x-app-layout>