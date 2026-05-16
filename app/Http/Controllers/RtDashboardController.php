<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Billing;
use App\Models\Agenda;
use Carbon\Carbon;

class RtDashboardController extends Controller
{
    public function index()
{
    // 1. DATA KARTU METRIK
    $totalWarga = User::where('role', 'warga')->count();
    
    $totalPemasukan = Billing::where('status', 'lunas')->sum('total_amount');
    $totalPengeluaran = Agenda::sum('realisasi_dana');
    $saldoAktual = $totalPemasukan - $totalPengeluaran;

    // --- PERBAIKAN LOGIKA KEPATUHAN (PAKAI ANGKA) ---
        $now = \Carbon\Carbon::now();
        $bulanAngka = $now->month; // Hasilnya: 5
        $bulanZero = $now->format('m'); // Hasilnya: "05"
        $tahunSekarang = $now->year;
        
        $wargaLunas = Billing::where('status', 'lunas')
            ->where('tahun', $tahunSekarang)
            ->where(function($q) use ($bulanAngka, $bulanZero) {
                $q->where('bulan', $bulanAngka)
                  ->orWhere('bulan', $bulanZero);
            })
            ->count();
            
        $persenKepatuhan = $totalWarga > 0 ? round(($wargaLunas / $totalWarga) * 100) : 0;
    // 2. LIST AGENDA TERDEKAT
    $agendaTerdekat = Agenda::where('tanggal', '>=', \Carbon\Carbon::now()->startOfDay())
        ->orderBy('tanggal', 'asc')
        ->take(4)
        ->get();

    // 3. DATA GRAFIK KEPATUHAN (6 BULAN TERAKHIR)
        $labelBulan = [];
        $dataKepatuhan = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subMonths($i);
            
            // Label tetap pakai nama bulan (Jan, Feb) agar bagus di grafik
            $labelBulan[] = $date->translatedFormat('M'); 

            // Ambil angka bulannya (1 untuk Jan, 5 untuk Mei)
            $bulanAngka = $date->month; 
            // Buat versi leading zero (01, 02... 05) untuk jaga-jaga
            $bulanZero = $date->format('m');

            // Kueri mencari angka bulan (misal 5 atau "05")
            $lunas = \App\Models\Billing::where('status', 'lunas')
                ->where('tahun', $date->year)
                ->where(function($q) use ($bulanAngka, $bulanZero) {
                    $q->where('bulan', $bulanAngka)
                      ->orWhere('bulan', $bulanZero);
                })
                ->count();
            
            $dataKepatuhan[] = $lunas;
        }

    return view('rt.dashboard', compact(
        'totalWarga', 'saldoAktual', 'persenKepatuhan', 
        'agendaTerdekat', 'labelBulan', 'dataKepatuhan'
    ));
}
}