<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Agenda;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class WargaDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $now = Carbon::now();

        // 1. Status Iuran Bulan Ini (bulan sudah integer)
        $statusBulanIni = Billing::where('user_id', $user->id)
            ->where('tahun', $now->year)
            ->where('bulan', $now->month)
            ->first();

        // 2. Data Grafik: Transparansi & Riwayat (6 Bulan Terakhir)
        $labelBulan = [];
        $dataBayarSaya = [];
        $dataPengeluaranRT = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labelBulan[] = $date->translatedFormat('M');

            // Nominal yang saya bayar di bulan ini (bulan sudah integer)
            $bayar = Billing::where('user_id', $user->id)
                ->where('status', 'lunas')
                ->where('tahun', $date->year)
                ->where('bulan', $date->month)
                ->sum('total_amount');
            
            // Total pengeluaran RT di bulan ini (Transparansi)
            $pengeluaran = Agenda::whereYear('tanggal', $date->year)
                ->whereMonth('tanggal', $date->month)
                ->sum('realisasi_dana');

            $dataBayarSaya[] = (int) $bayar;
            $dataPengeluaranRT[] = (int) $pengeluaran;
        }

        // 3. Agenda Terdekat
        $agendaTerdekat = Agenda::where('tanggal', '>=', $now->startOfDay())
            ->orderBy('tanggal', 'asc')->take(3)->get();

        return view('warga.dashboard', compact(
            'statusBulanIni', 
            'agendaTerdekat', 
            'labelBulan',  
            'dataBayarSaya', 
            'dataPengeluaranRT'
        ));
    }
}