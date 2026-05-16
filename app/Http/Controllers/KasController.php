<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Billing;
use App\Models\Agenda;
use App\Models\User;
use Carbon\Carbon;

class KasController extends Controller
{
    public function index(Request $request)
    {
        $tahunIni = $request->tahun ?? Carbon::now()->year;

        // 1. Saldo & Total
        $totalMasuk = Billing::where('status', 'lunas')->sum('total_amount') ?? 0;
        $totalKeluar = Agenda::sum('realisasi_dana') ?? 0;
        $saldoAktual = $totalMasuk - $totalKeluar;

        // 2. Tunggakan & Jumlah Warga Nunggak
        $tunggakanQuery = Billing::whereIn('status', ['belum_lunas', 'pending']);
        $totalTunggakanNominal = $tunggakanQuery->sum('total_amount') ?? 0;
        // Menghitung berapa banyak warga unik yang punya tunggakan
        $jumlahWargaNunggak = $tunggakanQuery->distinct('user_id')->count('user_id') ?? 0;

        // 3. Rekap 12 Bulan untuk Grafik/List
        $grafikBulanan = [];
        $namaBulan = [
            1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni',
            7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'
        ];

        foreach ($namaBulan as $num => $nama) {
            // Pemasukan per bulan (berdasarkan kolom bulan & tahun di billings)
            $masuk = Billing::where('status', 'lunas')
                ->where('tahun', $tahunIni)
                ->where(function($q) use ($nama, $num) {
                    $q->where('bulan', $nama)
                      ->orWhere('bulan', str_pad($num, 2, '0', STR_PAD_LEFT))
                      ->orWhere('bulan', $num);
                })->sum('total_amount') ?? 0;

            // Pengeluaran per bulan (berdasarkan tanggal acara di agendas)
            $keluar = Agenda::whereYear('tanggal', $tahunIni)
                ->whereMonth('tanggal', $num)
                ->sum('realisasi_dana') ?? 0;

            $grafikBulanan[] = [
                'bulan'  => $nama,
                'masuk'  => $masuk,
                'keluar' => $keluar
            ];
        }

        return view('rt.laporan.kas', compact(
            'saldoAktual', 'totalMasuk', 'totalKeluar', 'totalTunggakanNominal', 
            'jumlahWargaNunggak', 'grafikBulanan', 'tahunIni'
        ));
    }

    public function iuranWarga(Request $request)
    {
        $tahun = $request->tahun ?? Carbon::now()->year;
        $wargaList = User::where('role', 'warga')->with(['billings' => function($query) use ($tahun) {
            $query->where('tahun', $tahun);
        }])->get();

        return view('rt.laporan.iuran', compact('wargaList', 'tahun'));
    }
}