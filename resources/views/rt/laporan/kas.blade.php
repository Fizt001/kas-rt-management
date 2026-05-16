<x-app-layout>
    @php
        // Normalisasi data bulanan agar pas dan rapi saat dirender di Chart.js
        $monthsArray = [];
        $masukArray = [];
        $keluarArray = [];
        
        $shortMonths = [
            'Januari' => 'JAN', 'Februari' => 'FEB', 'Maret' => 'MAR', 'April' => 'APR',
            'Mei' => 'MEI', 'Juni' => 'JUN', 'Juli' => 'JUL', 'Agustus' => 'AGS',
            'September' => 'SEP', 'Oktober' => 'OKT', 'November' => 'NOV', 'Desember' => 'DES'
        ];

        foreach($grafikBulanan as $item) {
            $monthsArray[] = $shortMonths[$item['bulan']] ?? strtoupper(substr($item['bulan'], 0, 3));
            $masukArray[] = $item['masuk'];
            $keluarArray[] = $item['keluar'];
        }
    @endphp

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex flex-col">
                <h2 class="text-xl font-black text-slate-800 dark:text-white tracking-tight leading-tight uppercase">
                    Laporan <span class="text-blue-600">Keuangan Kas</span>
                </h2>
                <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1">Audit Arus Pemasukan & Realisasi Dana Kegiatan RT</p>
            </div>
            
            {{-- Filter Tahun Berjalan --}}
            <form action="{{ route('laporan.kas') }}" method="GET" class="w-full sm:w-auto">
                <select name="tahun" onchange="this.form.submit()" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl py-2 px-4 text-xs font-black focus:ring-2 focus:ring-blue-600 cursor-pointer text-slate-700 dark:text-slate-200 uppercase tracking-widest shadow-sm">
                    @for($i = date('Y') - 1; $i <= date('Y') + 1; $i++)
                        <option value="{{ $i }}" {{ $tahunIni == $i ? 'selected' : '' }}>THN {{ $i }}</option>
                    @endfor
                </select>
            </form>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6 max-w-7xl mx-auto space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-[2rem] p-6 shadow-xl shadow-blue-500/10 text-white relative overflow-hidden group">
                <svg class="absolute -right-4 -top-4 opacity-15 size-28 transition-transform group-hover:scale-110 duration-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.64-2.25 1.64-1.74 0-2.26-.95-2.32-1.81H7.84c.09 1.71 1.25 2.85 3.06 3.2V19h2.33v-1.66c1.64-.32 2.92-1.38 2.92-3.03 0-2.19-1.76-2.8-3.84-3.17z"/></svg>
                <div class="relative z-10 flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-black text-blue-200 uppercase tracking-[0.2em] mb-1">Saldo Kas Aktual</p>
                        <h1 class="text-3xl font-black tracking-tighter drop-shadow-sm">Rp{{ number_format($saldoAktual, 0, ',', '.') }}</h1>
                    </div>
                    <span class="text-[9px] font-black bg-white/20 px-3 py-1 rounded-md uppercase tracking-wider backdrop-blur-sm">{{ $saldoAktual > 0 ? 'Aman' : 'Defisit' }}</span>
                </div>
            </div>

            <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-[2rem] p-6 shadow-xl shadow-emerald-500/10 text-white relative overflow-hidden group">
                <svg class="absolute -right-4 -top-4 opacity-15 size-28 transition-transform group-hover:scale-110 duration-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                <div class="relative z-10 flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-black text-emerald-100 uppercase tracking-[0.2em] mb-1">Total Pemasukan</p>
                        <h1 class="text-3xl font-black tracking-tighter drop-shadow-sm">Rp{{ number_format($totalMasuk, 0, ',', '.') }}</h1>
                    </div>
                    <span class="text-[9px] font-black bg-white/20 px-3 py-1 rounded-md uppercase tracking-wider backdrop-blur-sm">Live</span>
                </div>
            </div>

            <div class="bg-gradient-to-br from-rose-500 to-pink-600 rounded-[2rem] p-6 shadow-xl shadow-rose-500/10 text-white relative overflow-hidden group">
                <svg class="absolute -right-4 -top-4 opacity-15 size-28 transition-transform group-hover:scale-110 duration-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div class="relative z-10 flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-black text-rose-100 uppercase tracking-[0.2em] mb-1">Total Tunggakan</p>
                        <h1 class="text-3xl font-black tracking-tighter drop-shadow-sm">Rp{{ number_format($totalTunggakanNominal, 0, ',', '.') }}</h1>
                    </div>
                    <span class="text-[9px] font-black bg-white/20 px-3 py-1 rounded-md uppercase tracking-wider backdrop-blur-sm">{{ $jumlahWargaNunggak }} Warga</span>
                </div>
            </div>

        </div>

        <div class="grid lg:grid-cols-10 gap-6">
            
            {{-- AREA KIRI: GRAFIK VISUALISASI LIVE (6 Kolom) --}}
            <div class="lg:col-span-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-6 rounded-[2.5rem] shadow-sm flex flex-col justify-between">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="w-2 h-6 bg-blue-600 rounded-full"></div>
                    <div>
                        <h3 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest">Visualisasi Arus Kas RT</h3>
                        <p class="text-[9px] text-slate-400 font-bold uppercase mt-0.5">Grafik Perbandingan Billings Masuk vs Dana Alokasi Agenda THN {{ $tahunIni }}</p>
                    </div>
                </div>
                {{-- Canvas Chart --}}
                <div class="relative w-full h-80">
                    <canvas id="chartLaporanBulananKas"></canvas>
                </div>
            </div>

            {{-- AREA KANAN: LIST RINCIAN DATA BULANAN SCROLLABLE (4 Kolom) --}}
            <div class="lg:col-span-4 bg-white dark:bg-slate-900 rounded-[2.5rem] p-6 shadow-sm border border-slate-100 dark:border-slate-800 flex flex-col justify-between">
                <div class="flex items-center gap-3 mb-4 pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="w-2 h-6 bg-indigo-500 rounded-full"></div>
                    <div>
                        <h3 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest">Buku Ledger Bulanan</h3>
                        <p class="text-[9px] font-bold text-slate-400 uppercase mt-0.5">Histori Kas Tiap Bulan</p>
                    </div>
                </div>

                {{-- List Scrollable Container --}}
                <div class="space-y-2.5 overflow-y-auto max-h-[22rem] pr-1 scrollbar-thin">
                    @foreach($grafikBulanan as $item)
                        @php 
                            $net = $item['masuk'] - $item['keluar']; 
                            $adaTransaksi = ($item['masuk'] > 0 || $item['keluar'] > 0);
                        @endphp
                        
                        <div class="p-3 rounded-2xl border transition-all flex flex-col gap-2 {{ $adaTransaksi ? 'border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30' : 'border-dashed border-slate-100 dark:border-slate-800 opacity-40 bg-transparent' }}">
                            <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-800/50 pb-1.5">
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-700 dark:text-slate-200">{{ $item['bulan'] }}</span>
                                <div class="text-right">
                                    <span class="text-[11px] font-black {{ $net > 0 ? 'text-emerald-500' : ($net < 0 ? 'text-rose-500' : 'text-slate-400') }}">
                                        {{ $net > 0 ? '+' : '' }}Rp{{ number_format($net, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-2 text-center">
                                <div class="bg-white dark:bg-slate-900 rounded-xl p-1.5 border border-slate-100 dark:border-slate-800/30">
                                    <p class="text-[7px] font-black text-slate-400 uppercase tracking-widest">Masuk</p>
                                    <p class="text-[10px] font-black mt-0.5 {{ $item['masuk'] > 0 ? 'text-blue-600 dark:text-blue-400' : 'text-slate-400' }}">
                                        Rp{{ number_format($item['masuk'], 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="bg-white dark:bg-slate-900 rounded-xl p-1.5 border border-slate-100 dark:border-slate-800/30">
                                    <p class="text-[7px] font-black text-slate-400 uppercase tracking-widest">Keluar</p>
                                    <p class="text-[10px] font-black mt-0.5 {{ $item['keluar'] > 0 ? 'text-amber-500 dark:text-amber-400' : 'text-slate-400' }}">
                                        Rp{{ number_format($item['keluar'], 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

    </div>

    {{-- SCRIPT GENERATE GRAPH TREN ARUS KAS LAPORAN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Menggunakan encoder asli untuk mencegah token error koma biner
            const labelsBulan = {!! json_encode($monthsArray) !!};
            const datasetMasuk = {!! json_encode($masukArray) !!};
            const datasetKeluar = {!! json_encode($keluarArray) !!};

            const ctxReport = document.getElementById('chartLaporanBulananKas').getContext('2d');
            new Chart(ctxReport, {
                type: 'bar',
                data: {
                    labels: labelsBulan,
                    datasets: [
                        {
                            type: 'line',
                            label: 'Tren Masuk',
                            data: datasetMasuk,
                            borderColor: '#3b82f6',
                            borderWidth: 2.5,
                            tension: 0.35,
                            fill: false,
                            pointBackgroundColor: '#3b82f6'
                        },
                        {
                            label: 'Total Masuk',
                            data: datasetMasuk,
                            backgroundColor: 'rgba(59, 130, 246, 0.85)',
                            borderRadius: 6,
                            barPercentage: 0.6,
                        },
                        {
                            label: 'Total Keluar',
                            data: datasetKeluar,
                            backgroundColor: 'rgba(245, 158, 11, 0.85)',
                            borderRadius: 6,
                            barPercentage: 0.6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: { font: { weight: 'bold', size: 10 } }
                        }
                    },
                    scales: {
                        y: {
                            grid: { color: 'rgba(148, 163, 184, 0.06)' },
                            ticks: { font: { weight: 'bold', size: 9 } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { weight: 'bold', size: 10 } }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>