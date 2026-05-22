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

    // --- LOGIKA KEPATUHAN (BULAN SUDAH STANDAR INTEGER) ---
        $now = \Carbon\Carbon::now();
        $bulanSekarang = $now->month; // Integer 1-12
        $tahunSekarang = $now->year;
        
        $wargaLunas = Billing::where('status', 'lunas')
            ->where('tahun', $tahunSekarang)
            ->where('bulan', $bulanSekarang)
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
            
            // Label pakai nama bulan singkat (Jan, Feb) agar bagus di grafik
            $labelBulan[] = $date->translatedFormat('M'); 

            // Query langsung pakai integer bulan (sudah standar)
            $lunas = \App\Models\Billing::where('status', 'lunas')
                ->where('tahun', $date->year)
                ->where('bulan', $date->month)
                ->count();
            
            $dataKepatuhan[] = $lunas;
        }

    return view('rt.dashboard', compact(
        'totalWarga', 'saldoAktual', 'persenKepatuhan', 
        'agendaTerdekat', 'labelBulan', 'dataKepatuhan'
    ));
}
}