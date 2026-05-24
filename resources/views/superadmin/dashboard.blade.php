<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="text-xl font-black text-slate-800 dark:text-white tracking-tight leading-tight">
                Dashboard <span class="text-blue-600">Superadmin (Global)</span>
            </h2>
            <p class="text-[11px] text-slate-500 font-medium uppercase tracking-wider italic mt-1">Pusat Kendali Keseluruhan Sistem</p>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-[90rem] mx-auto space-y-6">
        
        <!-- 1. KARTU METRIK UTAMA -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            
            <div class="bg-gradient-to-br from-blue-600 to-blue-800 p-4 sm:p-6 rounded-[1.25rem] sm:rounded-3xl shadow-lg sm:shadow-xl shadow-blue-200/50 text-white relative overflow-hidden transition-all hover:-translate-y-1">
                <svg class="absolute -right-2 -top-2 sm:-right-4 sm:-top-4 opacity-10 size-16 sm:size-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
                <h3 class="text-[8px] sm:text-[10px] font-black uppercase text-blue-100 tracking-wider sm:tracking-[0.2em] leading-tight line-clamp-1">Total Pengguna</h3>
                <h2 class="text-xl sm:text-3xl font-black mt-1 sm:mt-2 tracking-tight">{{ $totalUsers }} <span class="text-[9px] sm:text-xs font-bold opacity-60 uppercase">Akun</span></h2>
                <div class="mt-2 sm:mt-4 flex items-center gap-1 sm:gap-2">
                    <span class="text-[8px] sm:text-[10px] bg-white/20 px-1.5 sm:px-2 py-0.5 rounded-full font-bold line-clamp-1">Superadmin</span>
                </div>
            </div>

            <div class="bg-gradient-to-br from-slate-800 to-slate-950 p-4 sm:p-6 rounded-[1.25rem] sm:rounded-3xl shadow-lg sm:shadow-xl shadow-slate-200/50 text-white relative overflow-hidden transition-all hover:-translate-y-1">
                <svg class="absolute -right-2 -top-2 sm:-right-4 sm:-top-4 opacity-10 size-16 sm:size-24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <h3 class="text-[8px] sm:text-[10px] font-black uppercase text-slate-400 tracking-wider sm:tracking-[0.2em] leading-tight line-clamp-1">Saldo Kas RT</h3>
                <h2 class="text-xl sm:text-3xl font-black mt-1 sm:mt-2 tracking-tight">Rp{{ number_format($saldoAktual / 1000, 0, ',', '.') }}<span class="text-[10px] sm:hidden opacity-60">K</span><span class="hidden sm:inline">.000</span></h2>
                <div class="mt-2 sm:mt-4 flex items-center gap-1 sm:gap-2">
                    <span class="size-1.5 sm:size-2 bg-emerald-500 rounded-full animate-pulse shrink-0"></span>
                    <span class="text-[8px] sm:text-[10px] font-bold text-emerald-400 uppercase tracking-widest line-clamp-1">Aman</span>
                </div>
            </div>

            <div class="bg-gradient-to-br from-rose-500 to-rose-700 p-4 sm:p-6 rounded-[1.25rem] sm:rounded-3xl shadow-lg sm:shadow-xl shadow-rose-200/50 text-white relative overflow-hidden transition-all hover:-translate-y-1">
                <svg class="absolute -right-2 -top-2 sm:-right-4 sm:-top-4 opacity-10 size-16 sm:size-24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <h3 class="text-[8px] sm:text-[10px] font-black uppercase text-rose-100 tracking-wider sm:tracking-[0.2em] leading-tight line-clamp-1">Tunggakan</h3>
                <h2 class="text-xl sm:text-3xl font-black mt-1 sm:mt-2 tracking-tight">Rp{{ number_format($totalTunggakan / 1000, 0, ',', '.') }}<span class="text-[10px] sm:hidden opacity-60">K</span><span class="hidden sm:inline">.000</span></h2>
                <div class="mt-2 sm:mt-4 flex items-center gap-1 sm:gap-2">
                    <span class="text-[8px] sm:text-[10px] font-bold text-rose-200 uppercase tracking-widest line-clamp-1">Piutang RT</span>
                </div>
            </div>

            <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 p-4 sm:p-6 rounded-[1.25rem] sm:rounded-3xl shadow-lg sm:shadow-xl shadow-indigo-200/50 text-white relative overflow-hidden transition-all hover:-translate-y-1">
                <svg class="absolute -right-2 -top-2 sm:-right-4 sm:-top-4 opacity-10 size-16 sm:size-24" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <h3 class="text-[8px] sm:text-[10px] font-black uppercase text-indigo-100 tracking-wider sm:tracking-[0.2em] leading-tight line-clamp-1">Kepatuhan</h3>
                <h2 class="text-xl sm:text-3xl font-black mt-1 sm:mt-2 tracking-tight">{{ $persenKepatuhan }}%</h2>
                <div class="mt-2 sm:mt-4 w-full bg-white/10 h-1 sm:h-1.5 rounded-full overflow-hidden">
                    <div class="bg-indigo-400 h-full rounded-full transition-all duration-1000" style="width: {{ $persenKepatuhan }}%"></div>
                </div>
            </div>
            
        </div>

        <!-- 2. GRAFIK KEUANGAN & ROLE -->
        <div class="grid lg:grid-cols-3 gap-4 sm:gap-6">
            <div class="lg:col-span-2 p-4 sm:p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm rounded-[1.25rem] sm:rounded-3xl">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-[0.15em]">Tren Arus Kas (6 Bulan)</h2>
                        <p class="text-xs font-bold text-slate-400 mt-1">Perbandingan Pemasukan & Pengeluaran</p>
                    </div>
                </div>
                <div id="chart-superadmin-trenkas" class="w-full"></div>
            </div>

            <div class="p-4 sm:p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm rounded-[1.25rem] sm:rounded-3xl flex flex-col">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-[0.15em]">Distribusi Peran</h2>
                        <p class="text-xs font-bold text-slate-400 mt-1">Proporsi Akun Terdaftar</p>
                    </div>
                </div>
                <div class="flex-1 flex items-center justify-center">
                    <div id="chart-superadmin-roledist" class="w-full"></div>
                </div>
            </div>
        </div>

        <!-- 3. GRAFIK DEMOGRAFI & STATUS -->
        <div class="grid lg:grid-cols-3 gap-4 sm:gap-6">
            <div class="p-4 sm:p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm rounded-[1.25rem] sm:rounded-3xl flex flex-col">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-[0.15em]">Partisipasi Iuran</h2>
                        <p class="text-xs font-bold text-slate-400 mt-1">KK Melunasi Iuran per Bulan</p>
                    </div>
                </div>
                <div class="flex-1 flex items-center justify-center">
                    <div id="chart-superadmin-kepatuhan" class="w-full"></div>
                </div>
            </div>

            <div class="p-4 sm:p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm rounded-[1.25rem] sm:rounded-3xl flex flex-col">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-[0.15em]">Status Iuran Bulan Ini</h2>
                        <p class="text-xs font-bold text-slate-400 mt-1">Lunas vs Pending vs Nunggak</p>
                    </div>
                </div>
                <div class="flex-1 flex items-center justify-center">
                    <div id="chart-superadmin-statusiuran" class="w-full"></div>
                </div>
            </div>

            <div class="p-4 sm:p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm rounded-[1.25rem] sm:rounded-3xl flex flex-col">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-[0.15em]">Demografi Warga</h2>
                        <p class="text-xs font-bold text-slate-400 mt-1">Berdasarkan Kelompok Umur ({{ $totalJiwa }} Jiwa)</p>
                    </div>
                </div>
                <div class="flex-1 flex items-center justify-center">
                    <div id="chart-superadmin-demografi" class="w-full"></div>
                </div>
            </div>
        </div>

        <!-- 4. DAFTAR LOG / AKTIVITAS TERBARU -->
        <div class="grid lg:grid-cols-4 sm:grid-cols-2 gap-6 border-t border-slate-200 dark:border-slate-800 pt-6">
            
            <!-- A. Pendaftaran Terbaru -->
            <div class="p-6 bg-white dark:bg-slate-900 shadow-sm rounded-3xl border border-slate-100 dark:border-slate-800 flex flex-col">
                <h2 class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-widest mb-4">Pendaftaran Terbaru</h2>
                <div class="space-y-3 flex-1">
                    @forelse($usersTerbaru as $user)
                        <div class="flex items-center gap-3 p-2 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                            <div class="size-8 bg-blue-100 text-blue-600 rounded-lg flex justify-center items-center font-black uppercase text-xs">{{ substr($user->name, 0, 1) }}</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[10px] font-black text-slate-800 dark:text-slate-200 truncate">{{ $user->name }}</p>
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">{{ $user->role }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-[9px] text-center text-slate-400 py-4 uppercase">Tidak ada pendaftaran</p>
                    @endforelse
                </div>
                <a href="{{ route('users.index') }}" class="block text-center mt-4 text-[9px] font-black text-blue-600 hover:underline uppercase tracking-widest">Semua Akun &rarr;</a>
            </div>

            <!-- B. Antrean Verifikasi -->
            <div class="p-6 bg-white dark:bg-slate-900 shadow-sm rounded-3xl border border-slate-100 dark:border-slate-800 flex flex-col">
                <h2 class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-widest mb-4">Antrean Verifikasi ({{ $pendingVerifikasi }})</h2>
                <div class="space-y-3 flex-1">
                    @forelse($antreanVerifikasi as $item)
                        <div class="flex items-center justify-between p-2 rounded-xl bg-amber-50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-900/30">
                            <div class="min-w-0">
                                <p class="text-[10px] font-black text-slate-800 dark:text-slate-200 truncate">{{ $item->user->name }}</p>
                                <p class="text-[8px] font-bold text-amber-600 uppercase tracking-widest">{{ $item->updated_at->diffForHumans() }}</p>
                            </div>
                            <span class="text-[10px] font-black text-amber-700">Rp{{ number_format($item->total_amount, 0, ',', '.') }}</span>
                        </div>
                    @empty
                        <p class="text-[9px] text-center text-slate-400 py-4 uppercase">Antrean kosong</p>
                    @endforelse
                </div>
            </div>

            <!-- C. Warga Tunggakan Tertinggi -->
            <div class="p-6 bg-white dark:bg-slate-900 shadow-sm rounded-3xl border border-slate-100 dark:border-slate-800 flex flex-col">
                <h2 class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-widest mb-4">Tunggakan Tertinggi</h2>
                <div class="space-y-3 flex-1">
                    @forelse($topTunggakan as $warga)
                        <div class="flex items-center justify-between p-2 rounded-xl bg-rose-50 dark:bg-rose-900/10 border border-rose-100 dark:border-rose-900/30">
                            <div class="min-w-0">
                                <p class="text-[10px] font-black text-slate-800 dark:text-slate-200 truncate">{{ $warga->name }}</p>
                            </div>
                            <span class="text-[10px] font-black text-rose-600">Rp{{ number_format($warga->total_tunggakan, 0, ',', '.') }}</span>
                        </div>
                    @empty
                        <p class="text-[9px] text-center text-emerald-500 py-4 uppercase font-bold">Tidak ada tunggakan 🎉</p>
                    @endforelse
                </div>
            </div>

            <!-- D. Riwayat Pengeluaran -->
            <div class="p-6 bg-white dark:bg-slate-900 shadow-sm rounded-3xl border border-slate-100 dark:border-slate-800 flex flex-col">
                <h2 class="text-[11px] font-black text-slate-800 dark:text-white uppercase tracking-widest mb-4">Riwayat Kas Keluar</h2>
                <div class="space-y-3 flex-1">
                    @forelse($riwayatPengeluaran as $agenda)
                        <div class="flex items-center justify-between p-2 rounded-xl bg-emerald-50 dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-900/30">
                            <div class="min-w-0">
                                <p class="text-[10px] font-black text-slate-800 dark:text-slate-200 truncate">{{ $agenda->nama_agenda ?? $agenda->judul }}</p>
                                <p class="text-[8px] font-bold text-slate-400 uppercase">{{ \Carbon\Carbon::parse($agenda->tanggal)->translatedFormat('d M') }}</p>
                            </div>
                            <span class="text-[10px] font-black text-rose-600">-Rp{{ number_format($agenda->realisasi_dana, 0, ',', '.') }}</span>
                        </div>
                    @empty
                        <p class="text-[9px] text-center text-slate-400 py-4 uppercase">Belum ada pengeluaran</p>
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
            
            // 1. TREN ARUS KAS (AREA)
            var optTrenKas = {
                chart: { type: 'area', height: isMobile ? 220 : 320, toolbar: { show: false }, fontFamily: 'Figtree, sans-serif' },
                series: [
                    { name: 'Pemasukan', data: @json($dataPemasukan) },
                    { name: 'Pengeluaran', data: @json($dataPengeluaranChart) }
                ],
                colors: ['#10b981', '#f43f5e'],
                fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 100] } },
                dataLabels: { enabled: false }, stroke: { curve: 'smooth', width: 3 },
                xaxis: { categories: @json($labelBulan), labels: { style: { colors: '#94a3b8', fontSize: '10px', fontWeight: 700 } } },
                yaxis: { labels: { style: { colors: '#94a3b8', fontSize: '10px', fontWeight: 700 }, formatter: (v) => "Rp" + (v/1000) + "K" } },
                grid: { borderColor: '#f1f5f9', strokeDashArray: 4 }
            };
            new ApexCharts(document.querySelector("#chart-superadmin-trenkas"), optTrenKas).render();

            // 2. DISTRIBUSI PERAN (DONUT)
            var optRole = {
                series: @json($dataRole),
                labels: ['Warga', 'RT', 'Bendahara', 'Superadmin'],
                chart: { type: 'donut', height: isMobile ? 220 : 320, fontFamily: 'Figtree, sans-serif' },
                colors: ['#0891b2', '#10b981', '#4f46e5', '#3b82f6'],
                plotOptions: { pie: { donut: { size: '70%', labels: { show: true, name: { fontSize: '10px', fontWeight: 900 }, value: { fontSize: '24px', fontWeight: 900 } } } } },
                dataLabels: { enabled: false }, legend: { position: 'bottom', fontSize: '10px', fontWeight: 800 }
            };
            new ApexCharts(document.querySelector("#chart-superadmin-roledist"), optRole).render();

            // 3. KEPATUHAN (BAR)
            var optKepatuhan = {
                chart: { type: 'bar', height: isMobile ? 200 : 250, toolbar: { show: false }, fontFamily: 'Figtree, sans-serif' },
                series: [{ name: 'KK Lunas', data: @json($dataKepatuhan) }],
                colors: ['#6366f1'],
                plotOptions: { bar: { borderRadius: 6, columnWidth: '40%', dataLabels: { position: 'top' } } },
                dataLabels: { enabled: true, formatter: (val) => val + " KK", offsetY: -20, style: { fontSize: '10px', colors: ["#94a3b8"], fontWeight: 900 } },
                xaxis: { categories: @json($labelBulan), labels: { style: { colors: '#94a3b8', fontSize: '10px', fontWeight: 700 } } },
                yaxis: { show: false }, grid: { borderColor: '#f1f5f9', strokeDashArray: 4 }
            };
            new ApexCharts(document.querySelector("#chart-superadmin-kepatuhan"), optKepatuhan).render();

            // 4. STATUS IURAN BULAN INI (DONUT)
            var optStatus = {
                series: @json($dataStatusIuran),
                labels: ['Lunas', 'Pending', 'Belum Lunas'],
                chart: { type: 'donut', height: isMobile ? 200 : 250, fontFamily: 'Figtree, sans-serif' },
                colors: ['#10b981', '#f59e0b', '#f43f5e'],
                plotOptions: { pie: { donut: { size: '65%' } } },
                dataLabels: { enabled: false }, legend: { position: 'bottom', fontSize: '10px', fontWeight: 800 }
            };
            new ApexCharts(document.querySelector("#chart-superadmin-statusiuran"), optStatus).render();

            // 5. DEMOGRAFI WARGA (DONUT)
            var optDemografi = {
                series: @json($dataDemografi),
                labels: ['Balita (0-5)', 'Anak (6-12)', 'Remaja (13-17)', 'Dewasa (18-59)', 'Lansia (60+)'],
                chart: { type: 'donut', height: isMobile ? 200 : 250, fontFamily: 'Figtree, sans-serif' },
                colors: ['#0ea5e9', '#8b5cf6', '#ec4899', '#10b981', '#f59e0b'],
                plotOptions: { pie: { donut: { size: '65%' } } },
                dataLabels: { enabled: false }, legend: { position: 'bottom', fontSize: '10px', fontWeight: 800 }
            };
            new ApexCharts(document.querySelector("#chart-superadmin-demografi"), optDemografi).render();
        });
    </script>
    @endpush
</x-app-layout>