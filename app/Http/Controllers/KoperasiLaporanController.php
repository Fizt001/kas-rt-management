<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KoperasiAccount;
use App\Models\KoperasiTransaction;
use Carbon\Carbon;

class KoperasiLaporanController extends Controller
{
    public function index(Request $request)
    {
        $tahunIni = $request->tahun ?? Carbon::now()->year;

        // 1. Menghitung Metrik Finansial Riil Koperasi
        $totalMasuk = KoperasiTransaction::where('type', 'setoran')->where('status', 'approved')->sum('amount') ?? 0;
        $totalKeluar = KoperasiTransaction::where('type', 'penarikan')->where('status', 'approved')->sum('amount') ?? 0;
        
        // Saldo Aktual diambil dari total simpanan yang mengendap di seluruh akun anggota
        $saldoAktual = KoperasiAccount::sum('total_saldo') ?? ($totalMasuk - $totalKeluar);

        // 2. Membuat Struktur Rekap Arus Kas 12 Bulan
        $grafikBulanan = [];
        $namaBulan = [
            1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni',
            7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'
        ];

        foreach ($namaBulan as $num => $nama) {
            // Filter setoran masuk koperasi per bulan
            $masuk = KoperasiTransaction::where('type', 'setoran')
                ->where('status', 'approved')
                ->whereYear('created_at', $tahunIni)
                ->whereMonth('created_at', $num)
                ->sum('amount') ?? 0;

            // Filter penarikan keluar koperasi per bulan
            $keluar = KoperasiTransaction::where('type', 'penarikan')
                ->where('status', 'approved')
                ->whereYear('created_at', $tahunIni)
                ->whereMonth('created_at', $num)
                ->sum('amount') ?? 0;

            $grafikBulanan[] = [
                'bulan'  => $nama,
                'masuk'  => $masuk,
                'keluar' => $keluar
            ];
        }

        return view('koperasi.laporan.kas', compact(
            'saldoAktual', 'totalMasuk', 'totalKeluar', 'grafikBulanan', 'tahunIni'
        ));
    }
}