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
        // Pemasukan (Total tagihan yang sudah lunas)
        $totalPemasukan = Billing::where('status', 'lunas')->sum('total_amount');
        
        // Pengeluaran (Total seluruh pengeluaran RT)
        $totalPengeluaran = Agenda::sum('realisasi_dana'); // Sesuaikan nama kolom jika beda
        
        $saldoAktual = $totalPemasukan - $totalPengeluaran;

        // Total Tunggakan (Status belum lunas)
        $totalTunggakan = Billing::where('status', 'belum_lunas')->sum('total_amount');

        // Antrean Verifikasi (Status pending / menunggu konfirmasi)
        $pendingVerifikasi = Billing::where('status', 'pending')->count();

        // Ambil 4 struk terbaru yang butuh diverifikasi untuk Action List
        $antreanVerifikasiList = Billing::with('user') // Pastikan ada relasi ke User
            ->where('status', 'pending')
            ->orderBy('updated_at', 'desc')
            ->take(4)
            ->get();

        // 2. DATA GRAFIK (6 BULAN TERAKHIR)
        $labelBulan = [];
        $dataPemasukan = [];
        $dataPengeluaran = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labelBulan[] = $date->translatedFormat('M'); // Contoh: Jan, Feb, Mar

            // Hitung pemasukan per bulan
            $masuk = Billing::where('status', 'lunas')
                ->whereYear('updated_at', $date->year)
                ->whereMonth('updated_at', $date->month)
                ->sum('total_amount');
            
            // Hitung pengeluaran per bulan (YANG BARU)
             $keluar = Agenda::whereYear('tanggal', $date->year) 
                ->whereMonth('tanggal', $date->month)
                ->sum('realisasi_dana');

            // Kirim angka aslinya langsung tanpa dibagi
            $dataPemasukan[] = (int) $masuk;
            $dataPengeluaran[] = (int) $keluar;
        }

        return view('bendahara.dashboard', [
            'saldoAktual' => $saldoAktual,
            'pendingVerifikasi' => $pendingVerifikasi,
            'totalTunggakan' => $totalTunggakan,
            'antreanVerifikasi' => $antreanVerifikasiList,
            'labelBulan' => $labelBulan,
            'dataPemasukan' => $dataPemasukan,
            'dataPengeluaran' => $dataPengeluaran
        ]);
    }
}