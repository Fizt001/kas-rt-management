<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Agenda;
use App\Models\Infaq; // Import Model Infaq
use App\Models\KoperasiAccount; // Import Model Koperasi
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class WargaDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $now = Carbon::now();

        // 1. Status Iuran Bulan Ini
        $statusBulanIni = Billing::where('user_id', $user->id)
            ->where('tahun', $now->year)
            ->where(function($q) use ($now) {
                $q->where('bulan', $now->month)->orWhere('bulan', $now->format('m'));
            })->first();

        // 2. Data Grafik: Transparansi & Riwayat (6 Bulan Terakhir)
        $labelBulan = [];
        $dataBayarSaya = [];
        $dataPengeluaranRT = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labelBulan[] = $date->translatedFormat('M');

            // Nominal yang saya bayar di bulan ini
            $bayar = Billing::where('user_id', $user->id)
                ->where('status', 'lunas')
                ->where('tahun', $date->year)
                ->where(function($q) use ($date) {
                    $q->where('bulan', $date->month)->orWhere('bulan', $date->format('m'));
                })->sum('total_amount');
            
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

        // 4. Data Mesjid (Ambil dari tabel Infaq yang statusnya terverifikasi)
        $totalInfaq = Infaq::where('user_id', $user->id)
            ->where('status', 'terverifikasi')
            ->sum('nominal');

        // 5. Data Koperasi (Ambil total_saldo dari akun warga)
        $akunKoperasi = KoperasiAccount::where('user_id', $user->id)->first();
        $totalTabungan = $akunKoperasi ? $akunKoperasi->total_saldo : 0;

        return view('warga.dashboard', compact(
            'statusBulanIni', 
            'totalInfaq', 
            'totalTabungan', // Kirim variabel ini ke view
            'agendaTerdekat', 
            'labelBulan', 
            'dataBayarSaya', 
            'dataPengeluaranRT'
        ));
    }
}