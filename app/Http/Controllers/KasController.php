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

        $masukPerBulan = Billing::where('status', 'lunas')
            ->where('tahun', $tahunIni)
            ->selectRaw('bulan, SUM(total_amount) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $keluarPerBulan = Agenda::whereYear('tanggal', $tahunIni)
            ->selectRaw('MONTH(tanggal) as bulan, SUM(realisasi_dana) as total')
            ->groupByRaw('MONTH(tanggal)')
            ->pluck('total', 'bulan');

        foreach ($namaBulan as $num => $nama) {
            $grafikBulanan[] = [
                'bulan'  => $nama,
                'masuk'  => $masukPerBulan[$num] ?? 0,
                'keluar' => $keluarPerBulan[$num] ?? 0
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