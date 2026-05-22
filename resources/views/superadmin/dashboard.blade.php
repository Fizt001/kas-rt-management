<x-app-layout>
    @php
        // =========================================================================
        // REAL KAS-RT CORE LOGIC: AMBIL DATA ASLI SESUAI DENGAN STRUKTUR DATABASE
        // =========================================================================

        // 1. Perhitungan Live Kas Lingkungan (RT) menggunakan Billing & Agenda
        $totalMasukRT = class_exists('\App\Models\Billing') ? \App\Models\Billing::where('status', 'lunas')->sum('total_amount') : 0;
        $totalKeluarRT = class_exists('\App\Models\Agenda') ? \App\Models\Agenda::sum('realisasi_dana') : 0;
        $totalKasRT = $totalMasukRT - $totalKeluarRT;

        // 2. Perhitungan Live Saldo Kas Mesjid (Infaq Terverifikasi)
        $saldoMesjid = class_exists('\App\Models\Infaq') ? \App\Models\Infaq::where('status', 'terverifikasi')->sum('nominal') : 0;

        // 3. Perhitungan Live Sektor Koperasi (Total Saldo Akun Anggota)
        $modalKoperasi = class_exists('\App\Models\KoperasiAccount') ? \App\Models\KoperasiAccount::sum('total_saldo') : 0;

        // 4. Perhitungan Jumlah Akun User Terdaftar
        $totalUsers = class_exists('\App\Models\User') ? \App\Models\User::count() : 0;

        // 5. Pengumpulan Data Tren Keuangan 6 Bulan Terakhir secara Akurat
        $months = [];
        $chartKasRT = [];
        $chartMesjid = [];
        $chartKoperasi = [];

        $namaBulanIndo = [
            1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni',
            7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'
        ];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->translatedFormat('M');
            
            $numBulan = $date->month;
            $tahunQuery = $date->year;
            $namaBulanEksplisit = $namaBulanIndo[$numBulan];

            // Hitung Selisih Bersih (Pemasukan - Pengeluaran) RT di bulan terkait
            $masukBulananRT = class_exists('\App\Models\Billing') ? \App\Models\Billing::where('status', 'lunas')
                ->where('tahun', $tahunQuery)
                ->where(function($q) use ($namaBulanEksplisit, $numBulan) {
                    $q->where('bulan', $namaBulanEksplisit)
                      ->orWhere('bulan', str_pad($numBulan, 2, '0', STR_PAD_LEFT))
                      ->orWhere('bulan', $numBulan);
                })->sum('total_amount') : 0;

            $keluarBulananRT = class_exists('\App\Models\Agenda') ? \App\Models\Agenda::whereYear('tanggal', $tahunQuery)
                ->whereMonth('tanggal', $numBulan)
                ->sum('realisasi_dana') : 0;

            $chartKasRT[] = $masukBulananRT - $keluarBulananRT;

            // Hitung Infaq Mesjid Masuk Bulanan
            $chartMesjid[] = class_exists('\App\Models\Infaq') ? \App\Models\Infaq::where('status', 'terverifikasi')
                ->whereMonth('created_at', $numBulan)
                ->whereYear('created_at', $tahunQuery)
                ->sum('nominal') : 0;

            // Hitung Setoran Koperasi Masuk Bulanan
            $chartKoperasi[] = class_exists('\App\Models\KoperasiTransaction') ? \App\Models\KoperasiTransaction::where('type', 'setoran')
                ->where('status', 'approved')
                ->whereMonth('created_at', $numBulan)
                ->whereYear('created_at', $tahunQuery)
                ->sum('amount') : 0;
        }

        // =========================================================================
        // DATA UNTUK DOUGHNUT CHART: Status Pembayaran IPL Per Bulan
        // =========================================================================
        $selectedMonth = request('month', now()->month);
        $selectedYear = request('year', now()->year);
        
        $tagihanLunas = class_exists('\App\Models\Billing') ? \App\Models\Billing::where('bulan', $selectedMonth)->where('tahun', $selectedYear)->where('status', 'lunas')->count() : 0;
        $tagihanBelum = class_exists('\App\Models\Billing') ? \App\Models\Billing::where('bulan', $selectedMonth)->where('tahun', $selectedYear)->whereIn('status', ['belum_lunas', 'pending'])->count() : 0;

    @endphp

    <x-slot name="header">
        <div class="flex flex-col sm flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex flex-col">
                <h2 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight uppercase">
                    KONTROL PANEL <span class="text-blue-600">UTAMA</span>
                </h2>
                <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1">Monitor ekosistem KAS-RT secara live dari database.</p>
            </div>
            
            <span class="text-[10px] font-black text-slate-500 bg-slate-100 dark:bg-slate-800 px-4 py-2 rounded-xl uppercase tracking-wider border border-slate-200 dark:border-slate-700">
                Live Sinkronisasi: {{ now()->translatedFormat('d F Y H:i') }} WIB
            </span>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6 max-w-7xl mx-auto space-y-6">

        {{-- 1. KARTU STATISTIK METRIK UTAMA --}}
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- SEKTOR RT --}}
            <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800/80 shadow-sm transition-all hover:shadow-md border-b-4 border-b-blue-500 group">
                <div class="flex justify-between items-start mb-4">
                    <div class="size-11 bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 rounded-2xl flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform">
                        <svg class="size-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M3 10h18M5 6l7-3 7 3v4H5V6z"/></svg>
                    </div>
                    <span class="text-[9px] font-black text-blue-600 bg-blue-50 dark:bg-blue-900/30 dark:text-blue-400 px-2.5 py-1 rounded-md uppercase tracking-wider">KAS RT</span>
                </div>
                <h4 class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Total Kas Lingkungan</h4>
                <p class="text-2xl font-black text-slate-800 dark:text-white tracking-tight mt-1">Rp {{ number_format($totalKasRT, 0, ',', '.') }}</p>
            </div>

            {{-- SEKTOR MESJID --}}
            <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800/80 shadow-sm transition-all hover:shadow-md border-b-4 border-b-emerald-500 group">
                <div class="flex justify-between items-start mb-4">
                    <div class="size-11 bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform">
                        <svg class="size-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 22V12h6v10M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9z"/></svg>
                    </div>
                    <span class="text-[9px] font-black text-emerald-600 bg-emerald-50 dark:bg-emerald-900/30 dark:text-emerald-400 px-2.5 py-1 rounded-md uppercase tracking-wider">MESJID</span>
                </div>
                <h4 class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Saldo Kas Mesjid</h4>
                <p class="text-2xl font-black text-slate-800 dark:text-white tracking-tight mt-1">Rp {{ number_format($saldoMesjid, 0, ',', '.') }}</p>
            </div>

            {{-- SEKTOR KOPERASI --}}
            <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800/80 shadow-sm transition-all hover:shadow-md border-b-4 border-b-amber-500 group">
                <div class="flex justify-between items-start mb-4">
                    <div class="size-11 bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 rounded-2xl flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform">
                        <svg class="size-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4zM3 6h18M16 10a4 4 0 0 1-8 0"/></svg>
                    </div>
                    <span class="text-[9px] font-black text-amber-600 bg-amber-50 dark:bg-amber-900/30 dark:text-amber-400 px-2.5 py-1 rounded-md uppercase tracking-wider">KOPERASI</span>
                </div>
                <h4 class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Perputaran Modal</h4>
                <p class="text-2xl font-black text-slate-800 dark:text-white tracking-tight mt-1">Rp {{ number_format($modalKoperasi, 0, ',', '.') }}</p>
            </div>

            {{-- TOTAL ACCOUNT --}}
            <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-100 dark:border-slate-800/80 shadow-sm transition-all hover:shadow-md border-b-4 border-b-slate-500 group">
                <div class="flex justify-between items-start mb-4">
                    <div class="size-11 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-2xl flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform">
                        <svg class="size-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2m16-10a4 4 0 1 1-8 0 4 4 0 0 1 8 0z"/></svg>
                    </div>
                    <span class="text-[9px] font-black text-slate-600 bg-slate-50 dark:bg-slate-700/50 dark:text-slate-300 px-2.5 py-1 rounded-md uppercase tracking-wider">SISTEM</span>
                </div>
                <h4 class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Total User Aktif</h4>
                <p class="text-2xl font-black text-slate-800 dark:text-white tracking-tight mt-1">{{ $totalUsers }} Akun</p>
            </div>
        </div>

        {{-- 2. GRID KANVAS GRAFIK UTAMA --}}
        <div class="grid lg:grid-cols-10 gap-6">
            {{-- LINE GRAPH (6 Kolom) --}}
            <div class="lg:col-span-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-6 rounded-[2.5rem] shadow-sm flex flex-col justify-between">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-6 border-b border-slate-50 dark:border-slate-800 pb-4">
                    <div>
                        <h3 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest">Tren Aliran Finansial</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase mt-0.5">Selisih Bersih Bulanan antar Sektor</p>
                    </div>
                    <span class="text-[9px] font-black text-blue-600 bg-blue-50 dark:bg-blue-900/30 px-3 py-1 rounded-full uppercase">Skala Semester</span>
                </div>
                <div class="relative w-full h-72">
                    <canvas id="chartArusKas"></canvas>
                </div>
            </div>

            {{-- DOUGHNUT GRAPH (4 Kolom) --}}
            <div class="lg:col-span-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-6 rounded-[2.5rem] shadow-sm flex flex-col justify-between relative">
                <div class="flex flex-col mb-4 border-b border-slate-50 dark:border-slate-800 pb-4">
                    <h3 class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest">Status Pembayaran IPL</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase mt-0.5">Lunas vs Belum Lunas</p>
                </div>

                {{-- Form Pilihan Bulan & Tahun --}}
                <form action="{{ url()->current() }}" method="GET" class="flex gap-2 mb-4">
                    <select name="month" class="bg-slate-50 border-none rounded-lg text-[10px] font-bold py-2 px-3 flex-1 focus:ring-2 focus:ring-emerald-500" onchange="this.form.submit()">
                        @foreach([1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'] as $num => $name)
                            <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    <select name="year" class="bg-slate-50 border-none rounded-lg text-[10px] font-bold py-2 px-3 flex-1 focus:ring-2 focus:ring-emerald-500" onchange="this.form.submit()">
                        @for($y = now()->year - 1; $y <= now()->year + 1; $y++)
                            <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </form>

                <div class="relative w-full h-56 flex items-center justify-center">
                    @if($tagihanLunas == 0 && $tagihanBelum == 0)
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <p class="text-[9px] font-black uppercase text-slate-300 text-center tracking-widest">Belum ada tagihan<br>digenerate</p>
                        </div>
                    @endif
                    <canvas id="chartDistribusiAsset"></canvas>
                </div>
            </div>
        </div>

        {{-- 3. INTERACTIVE BANNER NAVIGASI --}}
        <div class="bg-gradient-to-r from-slate-900 to-slate-950 dark:from-slate-900 dark:to-slate-900 border border-slate-800 rounded-[2.5rem] p-8 text-white relative overflow-hidden group shadow-xl">
            <div class="absolute -top-20 -right-20 size-64 bg-blue-500/10 rounded-full blur-3xl group-hover:bg-blue-500/20 transition-all duration-500"></div>
            <div class="relative z-10 flex flex-col sm:flex-row justify-between items-center gap-6">
                <div class="space-y-2 text-center sm:text-left">
                    <h3 class="text-lg font-black uppercase tracking-tight italic">Manajemen <span class="text-blue-500">Aktor & Akses</span></h3>
                    <p class="text-slate-400 text-xs max-w-xl leading-relaxed">
                        Kelola hak akses untuk seluruh aktor sistem (RT, Bendahara, Pengurus Mesjid, dan Koperasi) dalam satu tabel database terpusat.
                    </p>
                </div>
                <a href="{{ route('users.index') }}" class="w-full sm:w-auto text-center bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg active:scale-95 transition-all">
                    Buka Manajemen User
                </a>
            </div>
        </div>

    </div>

    {{-- SCRIPTS KONEKSI DATA REAL KE CHART JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            
            // Mengubah array PHP murni menjadi objek JavaScript
            const bulanLabels = {!! json_encode($months) !!};
            const dataKasRT = {!! json_encode($chartKasRT) !!};
            const dataMesjid = {!! json_encode($chartMesjid) !!};
            const dataKoperasi = {!! json_encode($chartKoperasi) !!};

            // 1. CONFIG GRAPH LINE (TREN LIVE BULANAN)
            const ctxKas = document.getElementById('chartArusKas').getContext('2d');
            new Chart(ctxKas, {
                type: 'line',
                data: {
                    labels: bulanLabels,
                    datasets: [
                        {
                            label: 'Kas RT (Netto)',
                            data: dataKasRT,
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.03)',
                            borderWidth: 3,
                            tension: 0.38,
                            fill: true
                        },
                        {
                            label: 'Kas Mesjid (Masuk)',
                            data: dataMesjid,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.03)',
                            borderWidth: 3,
                            tension: 0.38,
                            fill: true
                        },
                        {
                            label: 'Koperasi (Setoran)',
                            data: dataKoperasi,
                            borderColor: '#f59e0b',
                            backgroundColor: 'rgba(245, 158, 11, 0.03)',
                            borderWidth: 3,
                            tension: 0.38,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { labels: { font: { weight: 'bold', size: 10 } } }
                    },
                    scales: {
                        y: { grid: { color: 'rgba(148, 163, 184, 0.06)' }, ticks: { font: { weight: 'bold', size: 9 } } },
                        x: { grid: { display: false }, ticks: { font: { weight: 'bold', size: 10 } } }
                    }
                }
            });

            // 2. CONFIG GRAPH DOUGHNUT (STATUS PEMBAYARAN IPL)
            const ctxAsset = document.getElementById('chartDistribusiAsset').getContext('2d');
            const dataLunas = {{ $tagihanLunas }};
            const dataBelum = {{ $tagihanBelum }};

            new Chart(ctxAsset, {
                type: 'doughnut',
                data: {
                    labels: ['Sudah Bayar', 'Belum Tertagih'],
                    datasets: [{
                        data: (dataLunas === 0 && dataBelum === 0) ? [0.001] : [dataLunas, dataBelum],
                        backgroundColor: (dataLunas === 0 && dataBelum === 0) ? ['#f1f5f9'] : ['#10b981', '#f43f5e'],
                        borderWidth: 5,
                        borderColor: document.documentElement.classList.contains('dark') ? '#0f172a' : '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { font: { weight: 'bold', size: 10 }, padding: 15 } },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    if(dataLunas === 0 && dataBelum === 0) return ' Belum Ada Tagihan';
                                    return ' ' + context.label + ': ' + context.raw + ' Warga';
                                }
                            }
                        }
                    },
                    cutout: '72%'
                }
            });
        });
    </script>
</x-app-layout>