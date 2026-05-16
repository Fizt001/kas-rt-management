<?php

namespace App\Http\Controllers;

use App\Models\KoperasiAccount;
use App\Models\KoperasiTransaction;
use Illuminate\Http\Request;

class KoperasiDashboardController extends Controller
{
    public function index()
    {
        // 1. Total Semua Tabungan Warga
        $totalPokok = \App\Models\KoperasiAccount::sum('saldo_pokok');
        $totalWajib = \App\Models\KoperasiAccount::sum('saldo_wajib');
        $totalSukarela = \App\Models\KoperasiAccount::sum('saldo_sukarela');
        $totalSimpanan = $totalPokok + $totalWajib + $totalSukarela;

        // 2. Hitung Uang Beredar (Utang yang belum dibayar warga)
        $uangBeredar = \App\Models\KoperasiLoanInstallment::whereIn('status', ['belum_bayar', 'pending'])->sum('amount');

        // 3. Kas Fisik Tersedia
        $totalDana = $totalSimpanan - $uangBeredar;

        // 4. Hitung Antrean (Cuma count, jadi sangat ringan!)
        $countPendingTransaksi = \App\Models\KoperasiTransaction::where('status', 'pending')->count();
        $countPendingKasbon = \App\Models\KoperasiLoan::where('status', 'pending')->count();
        $countPendingCicilan = \App\Models\KoperasiLoanInstallment::where('status', 'pending')->count();

       // 5. Data untuk Chart Donut (Wajib di-cast ke integer agar JS bisa membacanya)
        $chartSimpanan = [
            (int) $totalPokok, 
            (int) $totalWajib, 
            (int) $totalSukarela
        ];
        
        // 6. Daftar Akun untuk Tabungan Teratas
        $accounts = \App\Models\KoperasiAccount::with('user')->get();

        return view('koperasi.dashboard', compact(
            'totalDana', 'totalPokok', 'totalWajib', 'totalSukarela', 
            'uangBeredar', 'countPendingTransaksi', 'countPendingKasbon', 'countPendingCicilan', 'accounts', 'chartSimpanan'
        ));
    }
}