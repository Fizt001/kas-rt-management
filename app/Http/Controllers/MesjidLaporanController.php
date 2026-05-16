<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Infaq;
use App\Models\MesjidExpenditure;
use Carbon\Carbon;

class MesjidLaporanController extends Controller
{
    public function index(Request $request)
    {
        // 1. Tentukan tahun yang sedang dilihat
        $tahunIni = $request->tahun ?? Carbon::now()->year;

        // 2. Hitung Saldo Keseluruhan (Hanya Infaq yang 'terverifikasi')
        $totalPemasukan = Infaq::where('status', 'terverifikasi')->sum('nominal') ?? 0;
        $totalPengeluaran = MesjidExpenditure::sum('nominal') ?? 0;
        $saldoAktual = $totalPemasukan - $totalPengeluaran;

        // 3. Siapkan kerangka untuk grafik 12 bulan
        $grafikBulanan = [];
        $namaBulan = [
            1=>'Jan', 2=>'Feb', 3=>'Mar', 4=>'Apr', 5=>'Mei', 6=>'Jun',
            7=>'Jul', 8=>'Ags', 9=>'Sep', 10=>'Okt', 11=>'Nov', 12=>'Des'
        ];

        // 4. Hitung masuk & keluar per masing-masing bulan
        foreach ($namaBulan as $num => $nama) {
            $masuk = Infaq::where('status', 'terverifikasi')
                ->whereYear('created_at', $tahunIni)
                ->whereMonth('created_at', $num)
                ->sum('nominal') ?? 0;

            $keluar = MesjidExpenditure::whereYear('tanggal', $tahunIni)
                ->whereMonth('tanggal', $num)
                ->sum('nominal') ?? 0;

            $grafikBulanan[] = [
                'bulan'  => $nama,
                'masuk'  => $masuk,
                'keluar' => $keluar
            ];
        }

        // 5. Kirim semua hasil hitungan ke tampilan (view) dengan aman
        return view('mesjid.laporan.index', compact(
            'saldoAktual', 'totalPemasukan', 'totalPengeluaran', 'grafikBulanan', 'tahunIni'
        ));
    }
}