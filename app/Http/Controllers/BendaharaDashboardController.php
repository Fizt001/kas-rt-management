<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Billing; // Asumsi model tagihan/iuran warga
use App\Models\Agenda;
use App\Models\Expenditure; // Asumsi model pengeluaran kas RT
use Carbon\Carbon;

class BendaharaDashboardController extends Controller
{
    public function index()
    {
        // 1. KARTU METRIK UTAMA
        $totalPemasukan = Billing::where('status', 'lunas')->sum('total_amount');
        $totalPengeluaran = Agenda::sum('realisasi_dana'); 
        $saldoAktual = $totalPemasukan - $totalPengeluaran;

        $totalTunggakan = Billing::whereIn('status', ['belum_lunas', 'pending'])->sum('total_amount');
        $pendingVerifikasi = Billing::where('status', 'pending')->count();

        $now = Carbon::now();
        $pemasukanBulanIni = Billing::where('status', 'lunas')
            ->where('tahun', $now->year)
            ->where('bulan', $now->month)
            ->sum('total_amount');

        // Status Iuran Bulan Ini (Donut Chart)
        $statusBulanIni = Billing::where('tahun', $now->year)
            ->where('bulan', $now->month)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');
            
        $dataStatusIuran = [
            $statusBulanIni['lunas'] ?? 0,
            $statusBulanIni['pending'] ?? 0,
            $statusBulanIni['belum_lunas'] ?? 0
        ];

        // Ambil 4 struk terbaru yang butuh diverifikasi untuk Action List
        $antreanVerifikasi = Billing::with('user')
            ->where('status', 'pending')
            ->orderBy('updated_at', 'desc')
            ->take(4)
            ->get();
            
        // Riwayat Pengeluaran (Agenda yang sudah ada realisasi dana)
        $riwayatPengeluaran = Agenda::where('realisasi_dana', '>', 0)
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get();

        // 2. DATA GRAFIK (6 BULAN TERAKHIR)
        $labelBulan = [];
        $dataPemasukan = [];
        $dataPengeluaranChart = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labelBulan[] = $date->translatedFormat('M');

            $masuk = Billing::where('status', 'lunas')
                ->where('tahun', $date->year)
                ->where('bulan', $date->month)
                ->sum('total_amount');
            
             $keluar = Agenda::whereYear('tanggal', $date->year) 
                ->whereMonth('tanggal', $date->month)
                ->sum('realisasi_dana');

            $dataPemasukan[] = (int) $masuk;
            $dataPengeluaranChart[] = (int) $keluar;
        }

        return view('bendahara.dashboard', compact(
            'saldoAktual', 'pendingVerifikasi', 'totalTunggakan', 'pemasukanBulanIni',
            'antreanVerifikasi', 'riwayatPengeluaran', 'dataStatusIuran',
            'labelBulan', 'dataPemasukan', 'dataPengeluaranChart'
        ));
    }
}